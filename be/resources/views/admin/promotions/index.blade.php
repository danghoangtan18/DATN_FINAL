@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Quản lý Khuyến mãi</li>
                    </ol>
                </div>
                <h4 class="page-title">Quản lý Khuyến mãi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-gift me-1"></i>
                        Danh sách Khuyến mãi
                    </h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Thêm khuyến mãi mới
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($promotions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 10%">Hình ảnh</th>
                                        <th style="width: 20%">Tiêu đề</th>
                                        <th style="width: 15%">Loại giảm giá</th>
                                        <th style="width: 10%">Giá trị</th>
                                        <th style="width: 15%">Thời gian</th>
                                        <th style="width: 10%">Trạng thái</th>
                                        <th style="width: 10%">Sử dụng</th>
                                        <th style="width: 5%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promotions as $promotion)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($promotion->image)
                                                <img src="{{ asset($promotion->image) }}" 
                                                     alt="{{ $promotion->title }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 40px; border-radius: 4px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $promotion->title }}</strong>
                                            @if($promotion->promotion_code)
                                                <br><small class="text-muted">Code: {{ $promotion->promotion_code }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                @switch($promotion->discount_type)
                                                    @case('percentage')
                                                        Phần trăm
                                                        @break
                                                    @case('fixed_amount')
                                                        Số tiền cố định
                                                        @break
                                                    @case('buy_x_get_y')
                                                        Mua X tặng Y
                                                        @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-danger">{{ $promotion->discount_display }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                Từ: {{ $promotion->start_date->format('d/m/Y H:i') }}<br>
                                                Đến: {{ $promotion->end_date->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $promotion->status_color }}">
                                                @switch($promotion->status)
                                                    @case('active')
                                                        Đang hoạt động
                                                        @break
                                                    @case('upcoming')
                                                        Sắp diễn ra
                                                        @break
                                                    @case('expired')
                                                        Đã hết hạn
                                                        @break
                                                    @case('inactive')
                                                        Tạm dừng
                                                        @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            @if($promotion->usage_limit)
                                                {{ $promotion->used_count }}/{{ $promotion->usage_limit }}
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ ($promotion->used_count / $promotion->usage_limit) * 100 }}%">
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Không giới hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.promotions.show', $promotion) }}" 
                                                   class="btn btn-info btn-sm" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                                                   class="btn btn-warning btn-sm" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Toggle Active/Inactive -->
                                                <form action="{{ route('admin.promotions.toggle', $promotion) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-{{ $promotion->is_active ? 'secondary' : 'success' }} btn-sm"
                                                            title="{{ $promotion->is_active ? 'Tạm dừng' : 'Kích hoạt' }}"
                                                            onclick="return confirm('Bạn có chắc chắn muốn {{ $promotion->is_active ? 'tạm dừng' : 'kích hoạt' }} khuyến mãi này?')">
                                                        <i class="fas fa-{{ $promotion->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.promotions.destroy', $promotion) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            title="Xóa"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $promotions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có khuyến mãi nào</h5>
                            <p class="text-muted">Hãy tạo khuyến mãi đầu tiên để thu hút khách hàng!</p>
                            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Tạo khuyến mãi mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.card-tools {
    position: absolute;
    right: 1rem;
    top: 0.75rem;
}

.table-responsive {
    border-radius: 0.5rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.progress {
    background-color: #e9ecef;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection