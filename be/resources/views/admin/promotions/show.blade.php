@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Khuyến mãi</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
                <h4 class="page-title">Chi tiết Khuyến mãi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Thông tin Khuyến mãi
                    </h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{ $promotion->title }}</h4>
                            <p class="text-muted">{{ $promotion->description }}</p>

                            <div class="mt-4">
                                <h6><strong>Chi tiết giảm giá:</strong></h6>
                                <ul class="list-unstyled">
                                    <li><strong>Loại:</strong> 
                                        <span class="badge bg-info">
                                            @switch($promotion->discount_type)
                                                @case('percentage') Phần trăm @break
                                                @case('fixed_amount') Số tiền cố định @break
                                                @case('buy_x_get_y') Mua X tặng Y @break
                                            @endswitch
                                        </span>
                                    </li>
                                    <li class="mt-1"><strong>Giá trị:</strong> 
                                        <span class="text-danger fw-bold fs-5">{{ $promotion->discount_display }}</span>
                                    </li>
                                    
                                    @if($promotion->minimum_order_amount)
                                        <li class="mt-1"><strong>Đơn hàng tối thiểu:</strong> 
                                            {{ number_format($promotion->minimum_order_amount) }}đ
                                        </li>
                                    @endif
                                    
                                    @if($promotion->maximum_discount_amount)
                                        <li class="mt-1"><strong>Giảm tối đa:</strong> 
                                            {{ number_format($promotion->maximum_discount_amount) }}đ
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <div class="mt-4">
                                <h6><strong>Thời gian hiệu lực:</strong></h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-calendar-alt text-success"></i> 
                                        <strong>Bắt đầu:</strong> {{ $promotion->start_date->format('d/m/Y H:i') }}
                                    </li>
                                    <li><i class="fas fa-calendar-alt text-danger"></i> 
                                        <strong>Kết thúc:</strong> {{ $promotion->end_date->format('d/m/Y H:i') }}
                                    </li>
                                    <li><i class="fas fa-clock text-info"></i> 
                                        <strong>Thời lượng:</strong> 
                                        {{ $promotion->start_date->diffInDays($promotion->end_date) }} ngày
                                    </li>
                                </ul>
                            </div>

                            @if($promotion->promotion_code)
                                <div class="mt-4">
                                    <h6><strong>Mã khuyến mãi:</strong></h6>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" class="form-control" value="{{ $promotion->promotion_code }}" 
                                               id="promo_code" readonly>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="copyToClipboard('promo_code')" title="Sao chép">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            @if($promotion->image)
                                <div class="text-center">
                                    <img src="{{ asset($promotion->image) }}" 
                                         alt="{{ $promotion->title }}" 
                                         class="img-fluid rounded shadow"
                                         style="max-height: 300px;">
                                </div>
                            @endif

                            <div class="mt-4">
                                <h6><strong>Trạng thái:</strong></h6>
                                <span class="badge bg-{{ $promotion->status_color }} fs-6">
                                    @switch($promotion->status)
                                        @case('active') 🟢 Đang hoạt động @break
                                        @case('upcoming') 🟡 Sắp diễn ra @break
                                        @case('expired') 🔴 Đã hết hạn @break
                                        @case('inactive') ⚫ Tạm dừng @break
                                    @endswitch
                                </span>
                            </div>

                            <div class="mt-4">
                                <h6><strong>Thống kê sử dụng:</strong></h6>
                                @if($promotion->usage_limit)
                                    <div class="d-flex justify-content-between">
                                        <span>Đã sử dụng:</span>
                                        <strong>{{ $promotion->used_count }}/{{ $promotion->usage_limit }}</strong>
                                    </div>
                                    <div class="progress mt-2" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $promotion->usage_limit > 0 ? ($promotion->used_count / $promotion->usage_limit) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Còn lại: {{ $promotion->usage_limit - $promotion->used_count }} lần
                                    </small>
                                @else
                                    <p class="text-muted">Không giới hạn số lần sử dụng</p>
                                    <p><strong>Đã sử dụng:</strong> {{ $promotion->used_count }} lần</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Điều kiện áp dụng -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-filter me-1"></i>
                        Điều kiện áp dụng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><strong>Danh mục áp dụng:</strong></h6>
                        @if($promotion->applicable_categories && count($promotion->applicable_categories) > 0)
                            @php
                                $categories = App\Models\Category::whereIn('Categories_ID', $promotion->applicable_categories)->get();
                            @endphp
                            @foreach($categories as $category)
                                <span class="badge bg-primary me-1 mb-1">{{ $category->Name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Áp dụng cho tất cả danh mục</span>
                        @endif
                    </div>

                    <div>
                        <h6><strong>Thương hiệu áp dụng:</strong></h6>
                        @if($promotion->applicable_brands && count($promotion->applicable_brands) > 0)
                            @php
                                $brands = App\Models\Brand::whereIn('Brand_ID', $promotion->applicable_brands)->get();
                            @endphp
                            @foreach($brands as $brand)
                                <span class="badge bg-success me-1 mb-1">{{ $brand->Name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Áp dụng cho tất cả thương hiệu</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thông tin hệ thống -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cog me-1"></i>
                        Thông tin hệ thống
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><strong>Tạo bởi:</strong> 
                            {{ $promotion->creator->name ?? 'N/A' }}
                        </li>
                        <li><strong>Ngày tạo:</strong> 
                            {{ $promotion->created_at->format('d/m/Y H:i') }}
                        </li>
                        <li><strong>Cập nhật cuối:</strong> 
                            {{ $promotion->updated_at->format('d/m/Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-tools me-1"></i>
                        Thao tác
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                        </a>

                        <form action="{{ route('admin.promotions.toggle', $promotion) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-{{ $promotion->is_active ? 'secondary' : 'success' }} w-100"
                                    onclick="return confirm('Bạn có chắc chắn muốn {{ $promotion->is_active ? 'tạm dừng' : 'kích hoạt' }} khuyến mãi này?')">
                                <i class="fas fa-{{ $promotion->is_active ? 'pause' : 'play' }} me-1"></i>
                                {{ $promotion->is_active ? 'Tạm dừng' : 'Kích hoạt' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này? Hành động này không thể hoàn tác!')">
                                <i class="fas fa-trash me-1"></i> Xóa khuyến mãi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    
    // Show toast or alert
    alert('Đã sao chép mã khuyến mãi: ' + element.value);
}
</script>

<style>
.card-tools {
    position: absolute;
    right: 1rem;
    top: 0.75rem;
}

.badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.5rem 1rem;
}

.progress {
    border-radius: 10px;
}

.list-unstyled li {
    padding: 0.25rem 0;
}

.input-group {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>
@endsection