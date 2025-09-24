<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\PostComment;
use App\Models\CommentRating;
use App\Models\Product;
use App\Models\Post;
use App\Models\User;

class CommentManagementController extends Controller
{
    /**
     * Lấy danh sách tất cả bình luận sản phẩm
     */
    public function getProductComments(Request $request)
    {
        $query = Comment::with(['product:Product_ID,Name', 'user:ID,Name,Email'])
            ->orderBy('Create_at', 'desc');

        // Lọc theo status nếu có
        if ($request->has('status') && $request->status !== '') {
            $query->where('Status', $request->status);
        }

        // Lọc theo sản phẩm nếu có
        if ($request->has('product_id') && $request->product_id) {
            $query->where('Product_ID', $request->product_id);
        }

        // Search theo nội dung
        if ($request->has('search') && $request->search) {
            $query->where('Content', 'like', '%' . $request->search . '%');
        }

        $comments = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Lấy danh sách tất cả bình luận bài viết
     */
    public function getPostComments(Request $request)
    {
        $query = PostComment::with(['post:Post_ID,Title', 'user:ID,Name,Email'])
            ->orderBy('created_at', 'desc');

        // Lọc theo bài viết nếu có
        if ($request->has('post_id') && $request->post_id) {
            $query->where('Post_ID', $request->post_id);
        }

        // Search theo nội dung
        if ($request->has('search') && $request->search) {
            $query->where('text', 'like', '%' . $request->search . '%');
        }

        $comments = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Cập nhật trạng thái bình luận sản phẩm
     */
    public function updateProductCommentStatus(Request $request, $commentId)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $comment = Comment::find($commentId);
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bình luận'
            ], 404);
        }

        $comment->Status = $request->status;
        $comment->Update_at = now();
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $comment
        ]);
    }

    /**
     * Xóa bình luận sản phẩm
     */
    public function deleteProductComment($commentId)
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bình luận'
            ], 404);
        }

        // Xóa các ratings liên quan trước
        CommentRating::where('Comment_ID', $commentId)->delete();
        
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bình luận thành công'
        ]);
    }

    /**
     * Xóa bình luận bài viết
     */
    public function deletePostComment($commentId)
    {
        $comment = PostComment::find($commentId);
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bình luận'
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bình luận thành công'
        ]);
    }

    /**
     * Lấy thống kê bình luận
     */
    public function getCommentStats()
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

        return response()->json([
            'success' => true,
            'data' => [
                'product_comments' => $productCommentStats,
                'post_comments' => $postCommentStats
            ]
        ]);
    }

    /**
     * Lấy danh sách sản phẩm để filter
     */
    public function getProductsForFilter()
    {
        $products = Product::select('Product_ID', 'Name')
            ->whereHas('comments')
            ->orderBy('Name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Lấy danh sách bài viết để filter
     */
    public function getPostsForFilter()
    {
        $posts = Post::select('Post_ID', 'Title')
            ->whereHas('comments')
            ->orderBy('Title')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Duyệt nhiều bình luận cùng lúc
     */
    public function bulkUpdateProductComments(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'integer|exists:comments,Comment_ID',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $updated = Comment::whereIn('Comment_ID', $request->comment_ids)
            ->update([
                'Status' => $request->status,
                'Update_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "Đã cập nhật {$updated} bình luận",
            'updated_count' => $updated
        ]);
    }

    /**
     * Xóa nhiều bình luận cùng lúc
     */
    public function bulkDeleteProductComments(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'integer|exists:comments,Comment_ID'
        ]);

        // Xóa ratings trước
        CommentRating::whereIn('Comment_ID', $request->comment_ids)->delete();
        
        // Xóa comments
        $deleted = Comment::whereIn('Comment_ID', $request->comment_ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "Đã xóa {$deleted} bình luận",
            'deleted_count' => $deleted
        ]);
    }

    /**
     * Xóa nhiều bình luận bài viết cùng lúc
     */
    public function bulkDeletePostComments(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'integer|exists:post_comments,ID'
        ]);

        $deleted = PostComment::whereIn('ID', $request->comment_ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "Đã xóa {$deleted} bình luận",
            'deleted_count' => $deleted
        ]);
    }
}