<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\PostComment;
use App\Models\CommentRating;
use App\Models\Product;
use App\Models\Post;

class CommentController extends Controller
{
    /**
     * Hiển thị danh sách bình luận sản phẩm
     */
    public function productComments(Request $request)
    {
        $query = Comment::with(['product:Product_ID,Name', 'user:ID,Name,Email'])
            ->orderBy('Create_at', 'desc');

        // Lọc theo status
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Lọc theo sản phẩm
        if ($request->filled('product_id')) {
            $query->where('Product_ID', $request->product_id);
        }

        // Search theo nội dung
        if ($request->filled('search')) {
            $query->where('Content', 'like', '%' . $request->search . '%');
        }

        $comments = $query->paginate(20);
        
        // Lấy danh sách sản phẩm có bình luận để filter
        $products = Product::select('Product_ID', 'Name')
            ->whereHas('comments')
            ->orderBy('Name')
            ->get();

        return view('admin.comments.product-comments', compact('comments', 'products'));
    }

    /**
     * Hiển thị danh sách bình luận bài viết
     */
    public function postComments(Request $request)
    {
        $query = PostComment::with(['post:Post_ID,Title', 'user:ID,Name,Email'])
            ->orderBy('created_at', 'desc');

        // Lọc theo bài viết
        if ($request->filled('post_id')) {
            $query->where('Post_ID', $request->post_id);
        }

        // Search theo nội dung
        if ($request->filled('search')) {
            $query->where('text', 'like', '%' . $request->search . '%');
        }

        $comments = $query->paginate(20);
        
        // Lấy danh sách bài viết có bình luận để filter
        $posts = Post::select('Post_ID', 'Title')
            ->whereHas('comments')
            ->orderBy('Title')
            ->get();

        return view('admin.comments.post-comments', compact('comments', 'posts'));
    }

    /**
     * Cập nhật trạng thái bình luận sản phẩm
     */
    public function updateProductCommentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $comment = Comment::findOrFail($id);
        $comment->Status = $request->status;
        $comment->Update_at = now();
        $comment->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái bình luận thành công!');
    }

    /**
     * Xóa bình luận sản phẩm
     */
    public function deleteProductComment($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Xóa các ratings liên quan trước
        CommentRating::where('Comment_ID', $id)->delete();
        
        $comment->delete();

        return redirect()->back()->with('success', 'Xóa bình luận thành công!');
    }

    /**
     * Xóa bình luận bài viết
     */
    public function deletePostComment($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Xóa bình luận thành công!');
    }

    /**
     * Xử lý bulk actions cho bình luận sản phẩm
     */
    public function bulkProductCommentAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'integer|exists:comments,Comment_ID'
        ]);

        $commentIds = $request->comment_ids;
        $action = $request->action;

        if ($action === 'delete') {
            // Xóa ratings trước
            CommentRating::whereIn('Comment_ID', $commentIds)->delete();
            // Xóa comments
            $deleted = Comment::whereIn('Comment_ID', $commentIds)->delete();
            return redirect()->back()->with('success', "Đã xóa {$deleted} bình luận");
        } else {
            // Cập nhật status
            $status = $action === 'approve' ? 'approved' : 'rejected';
            $updated = Comment::whereIn('Comment_ID', $commentIds)
                ->update([
                    'Status' => $status,
                    'Update_at' => now()
                ]);
            return redirect()->back()->with('success', "Đã cập nhật {$updated} bình luận");
        }
    }

    /**
     * Xử lý bulk delete cho bình luận bài viết
     */
    public function bulkDeletePostComments(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'integer|exists:post_comments,ID'
        ]);

        $deleted = PostComment::whereIn('ID', $request->comment_ids)->delete();

        return redirect()->back()->with('success', "Đã xóa {$deleted} bình luận");
    }

    /**
     * Hiển thị dashboard thống kê bình luận
     */
    public function dashboard()
    {
        $productCommentStats = [
            'total' => Comment::count(),
            'pending' => Comment::where('Status', 'pending')->count(),
            'approved' => Comment::where('Status', 'approved')->count(),
            'rejected' => Comment::where('Status', 'rejected')->count(),
        ];

        $postCommentStats = [
            'total' => PostComment::count(),
            'today' => PostComment::whereDate('created_at', today())->count(),
            'this_week' => PostComment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => PostComment::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.comments.dashboard', compact('productCommentStats', 'postCommentStats'));
    }
}