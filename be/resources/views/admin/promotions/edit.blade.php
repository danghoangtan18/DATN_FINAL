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
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </div>
                <h4 class="page-title">Chỉnh sửa Khuyến mãi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-1"></i>
                        Sửa Khuyến mãi: {{ $promotion->title }}
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Cột trái - Thông tin cơ bản -->
                            <div class="col-md-8">
                                <div class="row">
                                    <!-- Tiêu đề -->
                                    <div class="col-12 mb-3">
                                        <label for="title" class="form-label">Tiêu đề khuyến mãi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $promotion->title) }}" 
                                               placeholder="Ví dụ: Flash Sale cuối tuần">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Mô tả -->
                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4" 
                                                  placeholder="Mô tả chi tiết về chương trình khuyến mãi...">{{ old('description', $promotion->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Loại giảm giá và giá trị -->
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                                        <select class="form-select @error('discount_type') is-invalid @enderror" 
                                                id="discount_type" name="discount_type">
                                            <option value="">Chọn loại giảm giá</option>
                                            <option value="percentage" {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>
                                                Phần trăm (%)
                                            </option>
                                            <option value="fixed_amount" {{ old('discount_type', $promotion->discount_type) == 'fixed_amount' ? 'selected' : '' }}>
                                                Số tiền cố định (VNĐ)
                                            </option>
                                            <option value="buy_x_get_y" {{ old('discount_type', $promotion->discount_type) == 'buy_x_get_y' ? 'selected' : '' }}>
                                                Mua X tặng Y
                                            </option>
                                        </select>
                                        @error('discount_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="discount_value" class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('discount_value') is-invalid @enderror" 
                                               id="discount_value" name="discount_value" value="{{ old('discount_value', $promotion->discount_value) }}" 
                                               step="0.01" min="0" placeholder="0">
                                        @error('discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted" id="discount_hint"></small>
                                    </div>

                                    <!-- Điều kiện áp dụng -->
                                    <div class="col-md-6 mb-3">
                                        <label for="minimum_order_amount" class="form-label">Giá trị đơn hàng tối thiểu</label>
                                        <input type="number" class="form-control @error('minimum_order_amount') is-invalid @enderror" 
                                               id="minimum_order_amount" name="minimum_order_amount" 
                                               value="{{ old('minimum_order_amount', $promotion->minimum_order_amount) }}" 
                                               step="0.01" min="0" placeholder="0 (không giới hạn)">
                                        @error('minimum_order_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="maximum_discount_amount" class="form-label">Giảm giá tối đa</label>
                                        <input type="number" class="form-control @error('maximum_discount_amount') is-invalid @enderror" 
                                               id="maximum_discount_amount" name="maximum_discount_amount" 
                                               value="{{ old('maximum_discount_amount', $promotion->maximum_discount_amount) }}" 
                                               step="0.01" min="0" placeholder="0 (không giới hạn)">
                                        @error('maximum_discount_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Thời gian -->
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                               id="start_date" name="start_date" 
                                               value="{{ old('start_date', $promotion->start_date->format('Y-m-d\TH:i')) }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                               id="end_date" name="end_date" 
                                               value="{{ old('end_date', $promotion->end_date->format('Y-m-d\TH:i')) }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Mã khuyến mãi và giới hạn sử dụng -->
                                    <div class="col-md-6 mb-3">
                                        <label for="promotion_code" class="form-label">Mã khuyến mãi</label>
                                        <input type="text" class="form-control @error('promotion_code') is-invalid @enderror" 
                                               id="promotion_code" name="promotion_code" 
                                               value="{{ old('promotion_code', $promotion->promotion_code) }}" 
                                               placeholder="Để trống để tự động tạo">
                                        @error('promotion_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Mã khuyến mãi để khách hàng nhập khi thanh toán</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="usage_limit" class="form-label">Giới hạn sử dụng</label>
                                        <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                               id="usage_limit" name="usage_limit" 
                                               value="{{ old('usage_limit', $promotion->usage_limit) }}" 
                                               min="1" placeholder="0 (không giới hạn)">
                                        @error('usage_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-info">Đã sử dụng: {{ $promotion->used_count }} lần</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Cột phải - Cài đặt nâng cao -->
                            <div class="col-md-4">
                                <!-- Hình ảnh -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">Hình ảnh khuyến mãi</label>
                                    
                                    @if($promotion->image)
                                        <div class="mb-2">
                                            <img src="{{ asset($promotion->image) }}" class="img-thumbnail" style="max-width: 200px;">
                                            <small class="d-block text-muted">Hình ảnh hiện tại</small>
                                        </div>
                                    @endif
                                    
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="image_preview" class="mt-2" style="display: none;">
                                        <img id="preview_img" class="img-thumbnail" style="max-width: 200px;">
                                        <small class="d-block text-muted">Hình ảnh mới</small>
                                    </div>
                                </div>

                                <!-- Trạng thái -->
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Kích hoạt khuyến mãi
                                        </label>
                                    </div>
                                </div>

                                <!-- Áp dụng cho danh mục -->
                                <div class="mb-3">
                                    <label class="form-label">Áp dụng cho danh mục</label>
                                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px;">
                                        @foreach($categories as $category)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="applicable_categories[]" 
                                                       value="{{ $category->Categories_ID }}" id="cat_{{ $category->Categories_ID }}"
                                                       {{ in_array($category->Categories_ID, old('applicable_categories', $promotion->applicable_categories ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cat_{{ $category->Categories_ID }}">
                                                    {{ $category->Name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="form-text text-muted">Không chọn gì = áp dụng cho tất cả danh mục</small>
                                </div>

                                <!-- Áp dụng cho thương hiệu -->
                                <div class="mb-3">
                                    <label class="form-label">Áp dụng cho thương hiệu</label>
                                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px;">
                                        @foreach($brands as $brand)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="applicable_brands[]" 
                                                       value="{{ $brand->Brand_ID }}" id="brand_{{ $brand->Brand_ID }}"
                                                       {{ in_array($brand->Brand_ID, old('applicable_brands', $promotion->applicable_brands ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="brand_{{ $brand->Brand_ID }}">
                                                    {{ $brand->Name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="form-text text-muted">Không chọn gì = áp dụng cho tất cả thương hiệu</small>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="text-end">
                                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-times me-1"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Cập nhật khuyến mãi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Preview image
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_img').attr('src', e.target.result);
                $('#image_preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#image_preview').hide();
        }
    });

    // Update discount hint based on type
    function updateDiscountHint() {
        const type = $('#discount_type').val();
        let hint = '';
        
        switch(type) {
            case 'percentage':
                hint = 'Nhập số phần trăm giảm giá (0-100)';
                break;
            case 'fixed_amount':
                hint = 'Nhập số tiền giảm cố định (VNĐ)';
                break;
            case 'buy_x_get_y':
                hint = 'Nhập số lượng sản phẩm cần mua để được tặng 1';
                break;
        }
        
        $('#discount_hint').text(hint);
    }
    
    $('#discount_type').change(updateDiscountHint);
    updateDiscountHint(); // Initialize on load
});
</script>
@endsection