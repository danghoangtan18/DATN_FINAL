<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user', 'category')->where('Status', 1); // Chỉ lấy bài viết đã publish
        
        // Tìm kiếm theo từ khóa
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Title', 'LIKE', "%{$search}%")
                  ->orWhere('Excerpt', 'LIKE', "%{$search}%")
                  ->orWhere('Content', 'LIKE', "%{$search}%");
            });
        }
        
        // Lọc theo category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('Category_ID', $request->category);
        }
        
        // Sắp xếp mới nhất
        $query->orderBy('Created_at', 'desc');
        
        // Phân trang
        $perPage = $request->get('per_page', 12);
        $posts = $query->paginate($perPage);
        
        return response()->json([
            'data' => $posts->items(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'per_page' => $posts->perPage(),
            'total' => $posts->total(),
            'from' => $posts->firstItem(),
            'to' => $posts->lastItem()
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,id',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'required|in:1,0',
            'View' => 'nullable|integer',
        ]);

        $post = Post::create($validated);
        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::with('user', 'category')->find($id);
        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,id',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'required|in:1,0',
            'View' => 'nullable|integer',
        ]);

        $validated['Updated_at'] = now();

        $post->update($validated);
        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
