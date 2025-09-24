<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\NotificationService;
use App\Models\User;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.promotions.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'promotion_code' => 'nullable|string|unique:promotions,promotion_code',
            'usage_limit' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'applicable_categories' => 'nullable|array',
            'applicable_brands' => 'nullable|array',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/promotions'), $imageName);
            $data['image'] = 'uploads/promotions/' . $imageName;
        }

        // Generate promotion code if not provided
        if (empty($data['promotion_code'])) {
            $data['promotion_code'] = 'PROMO_' . strtoupper(Str::random(8));
        }

        // Set created_by
        $data['created_by'] = auth()->id();

        // Convert dates
        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);

        $promotion = Promotion::create($data);

        // Nếu khuyến mãi có hiệu lực ngay, gửi thông báo cho tất cả user
        if ($promotion->isActive()) {
            $activeUsers = User::where('Status', 1)->pluck('ID')->toArray();
            
            if (!empty($activeUsers)) {
                NotificationService::broadcast(
                    $activeUsers,
                    '🎊 Khuyến mãi mới!',
                    "Chương trình khuyến mãi '{$promotion->title}' với giá trị giảm {$promotion->discount_display} đã có hiệu lực! " . 
                    ($promotion->promotion_code ? "Sử dụng mã: {$promotion->promotion_code}. " : "") . 
                    "Nhanh tay mua sắm để không bỏ lỡ ưu đãi!",
                    'promotion',
                    '/promotions',
                    'fas fa-gift',
                    'normal'
                );
            }
        }

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được tạo thành công!');
    }

    public function show(Promotion $promotion)
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.promotions.edit', compact('promotion', 'categories', 'brands'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'promotion_code' => 'nullable|string|unique:promotions,promotion_code,' . $promotion->id,
            'usage_limit' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'applicable_categories' => 'nullable|array',
            'applicable_brands' => 'nullable|array',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($promotion->image && file_exists(public_path($promotion->image))) {
                unlink(public_path($promotion->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/promotions'), $imageName);
            $data['image'] = 'uploads/promotions/' . $imageName;
        }

        // Convert dates
        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được cập nhật thành công!');
    }

    public function destroy(Promotion $promotion)
    {
        // Delete image file
        if ($promotion->image && file_exists(public_path($promotion->image))) {
            unlink(public_path($promotion->image));
        }

        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được xóa thành công!');
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->update([
            'is_active' => !$promotion->is_active
        ]);

        $status = $promotion->is_active ? 'kích hoạt' : 'tạm dừng';
        
        return redirect()->route('admin.promotions.index')
            ->with('success', "Khuyến mãi đã được {$status}!");
    }

    // API methods for frontend
    public function apiIndex(Request $request)
    {
        $query = Promotion::query();

        if ($request->has('active_only') && $request->active_only) {
            $query->active();
        }

        if ($request->has('category_id')) {
            $query->whereJsonContains('applicable_categories', (string)$request->category_id);
        }

        if ($request->has('brand_id')) {
            $query->whereJsonContains('applicable_brands', (string)$request->brand_id);
        }

        $promotions = $query->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $promotions
        ]);
    }

    public function apiShow(Promotion $promotion)
    {
        return response()->json([
            'success' => true,
            'data' => $promotion
        ]);
    }

    public function validatePromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_amount' => 'nullable|numeric|min:0'
        ]);

        $promotion = Promotion::where('promotion_code', $request->code)
            ->active()
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn.'
            ]);
        }

        if (!$promotion->canBeUsed()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi đã đạt giới hạn sử dụng.'
            ]);
        }

        $orderAmount = $request->order_amount ?? 0;

        if ($promotion->minimum_order_amount && $orderAmount < $promotion->minimum_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($promotion->minimum_order_amount) . 'đ'
            ]);
        }

        // Calculate discount amount
        $discountAmount = 0;
        switch ($promotion->discount_type) {
            case 'percentage':
                $discountAmount = ($orderAmount * $promotion->discount_value) / 100;
                if ($promotion->maximum_discount_amount) {
                    $discountAmount = min($discountAmount, $promotion->maximum_discount_amount);
                }
                break;
            case 'fixed_amount':
                $discountAmount = $promotion->discount_value;
                break;
            case 'buy_x_get_y':
                // This would need more complex logic based on cart items
                $discountAmount = 0;
                break;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'promotion' => $promotion,
                'discount_amount' => $discountAmount,
                'final_amount' => max(0, $orderAmount - $discountAmount)
            ]
        ]);
    }
}