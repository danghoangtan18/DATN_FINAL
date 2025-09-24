@extends('layouts.layout')

@section('content')
<style>
    .comment-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fff;
    }
    .comment-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 13px;
        color: #6b7280;
    }
    .comment-content {
        background: #f9fafb;
        padding: 10px;
        border-radius: 6px;
        margin: 10px 0;
    }
    .filter-form {
        background: #f8fafc;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .bulk-actions {
        background: #eff6ff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        display: none;
    }
    .bulk-actions.show {
        display: block;
    }
</style>

<div class="head-title">
    <div class="left">
        <h1>Quản lý bình luận bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a href="{{ route('admin.comments.dashboard') }}">Quản lý bình luận</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li class="active">Bình luận bài viết</li>
        </ul>
    </div>
</div>

<!-- Filter Form -->
<form method="GET" class="filter-form">
    <div class="row align-items-end">
        <div class="col-md-4">
            <label for="search" class="form-label">Tìm kiếm nội dung</label>
            <input type="text" class="form-control" id="search" name="search" 
                   value="{{ request('search') }}" placeholder="Nhập nội dung bình luận...">
        </div>
        <div class="col-md-4">
            <label for="post_id" class="form-label">Bài viết</label>
            <select class="form-select" id="post_id" name="post_id">
                <option value="">Tất cả bài viết</option>
                @foreach($posts as $post)
                    <option value="{{ $post->Post_ID }}" 
                            {{ request('post_id') == $post->Post_ID ? 'selected' : '' }}>
                        {{ $post->Title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                <i class='bx bx-search'></i> Tìm kiếm
            </button>
            <a href="{{ route('admin.comments.post') }}" class="btn btn-secondary">
                <i class='bx bx-refresh'></i> Reset
            </a>
        </div>
    </div>
</form>

<!-- Bulk Actions -->
<div id="bulkActions" class="bulk-actions">
    <form id="bulkForm" method="POST" action="{{ route('admin.comments.post.bulk-delete') }}">
        @csrf
        <div class="row align-items-center">
            <div class="col-md-6">
                <span id="selectedCount">0</span> bình luận được chọn
            </div>
            <div class="col-md-6 text-end">
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Bạn có chắc muốn xóa những bình luận đã chọn?')">
                    <i class='bx bx-trash'></i> Xóa bình luận đã chọn
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Results -->
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Danh sách bình luận bài viết ({{ $comments->total() }})</h3>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">Chọn tất cả</label>
            </div>
        </div>
        
        @if($comments->count() > 0)
            @foreach($comments as $comment)
                <div class="comment-card">
                    <div class="form-check" style="float: left; margin-right: 15px;">
                        <input class="form-check-input comment-checkbox" type="checkbox" 
                               name="comment_ids[]" value="{{ $comment->ID }}">
                    </div>
                    
                    <div class="comment-meta">
                        <div>
                            <strong>{{ $comment->user->Name ?? 'Người dùng không xác định' }}</strong>
                            <span class="text-muted">({{ $comment->user->Email ?? 'N/A' }})</span>
                            <br>
                            <small>Bài viết: <strong>{{ $comment->post->Title ?? 'Bài viết đã bị xóa' }}</strong></small>
                        </div>
                        <div class="text-end">
                            <small>{{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : 'N/A' }}</small>
                        </div>
                    </div>
                    
                    <div class="comment-content">
                        {{ $comment->text }}
                    </div>
                    
                    <div class="comment-actions">
                        @if($comment->ID)
                        <form method="POST" action="{{ route('admin.comments.post.delete', ['id' => $comment->ID]) }}" 
                              style="display: inline-block;"
                              onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class='bx bx-trash'></i> Xóa
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $comments->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class='bx bx-message-dots' style="font-size: 64px; color: #ccc;"></i>
                <p class="text-muted">Không có bình luận nào.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.comment-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const bulkForm = document.getElementById('bulkForm');

    function updateBulkActions() {
        const checked = document.querySelectorAll('.comment-checkbox:checked');
        const count = checked.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
            bulkActions.classList.add('show');
            
            // Add hidden inputs for selected comment IDs
            const existingInputs = bulkForm.querySelectorAll('input[name="comment_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            checked.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'comment_ids[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else {
            bulkActions.classList.remove('show');
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            
            // Update select all checkbox
            const checkedCount = document.querySelectorAll('.comment-checkbox:checked').length;
            selectAll.checked = checkedCount === checkboxes.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        });
    });
});
</script>
@endpush

@endsection