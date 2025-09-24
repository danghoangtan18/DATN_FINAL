@extends('layouts.layout')

@section('content')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .stat-card.product {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .stat-card.post {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-number {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }
    .quick-action {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    .quick-action i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #3b82f6;
    }
    .recent-comments {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 20px;
    }
    .comment-item {
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .comment-item:last-child {
        border-bottom: none;
    }
</style>

<div class="head-title">
    <div class="left">
        <h1>Dashboard Quản lý Bình luận</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li class="active">Quản lý bình luận</li>
        </ul>
    </div>
</div>

<div class="table-data">
    <!-- Product Comments Stats -->
    <div class="order">
        <div class="head">
            <h3>Thống kê bình luận sản phẩm</h3>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card product">
                    <div class="stat-number">{{ $productCommentStats['total'] }}</div>
                    <div class="stat-label">Tổng bình luận</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card product">
                    <div class="stat-number">{{ $productCommentStats['pending'] }}</div>
                    <div class="stat-label">Chờ duyệt</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card product">
                    <div class="stat-number">{{ $productCommentStats['approved'] }}</div>
                    <div class="stat-label">Đã duyệt</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card product">
                    <div class="stat-number">{{ $productCommentStats['rejected'] }}</div>
                    <div class="stat-label">Từ chối</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Post Comments Stats -->
    <div class="order">
        <div class="head">
            <h3>Thống kê bình luận bài viết</h3>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card post">
                    <div class="stat-number">{{ $postCommentStats['total'] }}</div>
                    <div class="stat-label">Tổng bình luận</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card post">
                    <div class="stat-number">{{ $postCommentStats['today'] }}</div>
                    <div class="stat-label">Hôm nay</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card post">
                    <div class="stat-number">{{ $postCommentStats['this_week'] }}</div>
                    <div class="stat-label">Tuần này</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card post">
                    <div class="stat-number">{{ $postCommentStats['this_month'] }}</div>
                    <div class="stat-label">Tháng này</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="order">
        <div class="head">
            <h3>Thao tác nhanh</h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('admin.comments.product') }}" class="quick-action d-block">
                    <i class='bx bx-comment-dots'></i>
                    <h5>Quản lý bình luận sản phẩm</h5>
                    <p class="text-muted">Xem, duyệt và quản lý bình luận trên sản phẩm</p>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('admin.comments.post') }}" class="quick-action d-block">
                    <i class='bx bx-message-dots'></i>
                    <h5>Quản lý bình luận bài viết</h5>
                    <p class="text-muted">Xem và quản lý bình luận trên bài viết</p>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Comments -->
    <div class="order">
        <div class="head">
            <h3>Bình luận gần đây</h3>
        </div>
        <div class="recent-comments">
            @php
                $recentProductComments = \App\Models\Comment::with(['product:Product_ID,Name', 'user:ID,Name'])
                    ->orderBy('Create_at', 'desc')->limit(5)->get();
                $recentPostComments = \App\Models\PostComment::with(['post:Post_ID,Title', 'user:ID,Name'])
                    ->orderBy('created_at', 'desc')->limit(5)->get();
            @endphp
            
            <h6 class="text-primary">Bình luận sản phẩm mới nhất</h6>
            @forelse($recentProductComments as $comment)
                <div class="comment-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $comment->user->Name ?? 'N/A' }}</strong>
                            <span class="text-muted">đã bình luận sản phẩm</span>
                            <strong>{{ $comment->product->Name ?? 'N/A' }}</strong>
                        </div>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($comment->Create_at)->diffForHumans() }}
                        </small>
                    </div>
                    <p class="text-muted small mb-0">{{ Str::limit($comment->Content, 100) }}</p>
                </div>
            @empty
                <p class="text-muted">Chưa có bình luận sản phẩm nào.</p>
            @endforelse
            
            <hr>
            
            <h6 class="text-primary">Bình luận bài viết mới nhất</h6>
            @forelse($recentPostComments as $comment)
                <div class="comment-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $comment->user->Name ?? 'N/A' }}</strong>
                            <span class="text-muted">đã bình luận bài viết</span>
                            <strong>{{ $comment->post->Title ?? 'N/A' }}</strong>
                        </div>
                        <small class="text-muted">
                            {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <p class="text-muted small mb-0">{{ Str::limit($comment->text, 100) }}</p>
                </div>
            @empty
                <p class="text-muted">Chưa có bình luận bài viết nào.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection