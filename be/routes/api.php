<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductAttributeApiController;
use App\Http\Controllers\Api\ProductValueApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\VoucherApiController;
use App\Http\Controllers\Api\PostCategoryApiController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\ProductReviewApiController;
use App\Http\Controllers\Api\PostCommentApi;
use App\Http\Controllers\Api\CourtApi;
use App\Http\Controllers\Api\CourtBookingApi;
use App\Http\Controllers\Api\CartApi;
use App\Http\Controllers\Api\OrderDetailApi;
use App\Http\Controllers\Api\OrderApi;
use App\Http\Controllers\Api\RolesApiController;
use App\Http\Controllers\Api\BannerApiController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Api\PopupApiController;
use App\Http\Controllers\Admin\ChatBotController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\VnpayController;
use App\Http\Controllers\Api\LocationApi;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\CommentRatingApiController;
use App\Http\Controllers\Api\ProductRatingController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Auth\RegisterController;

// Products - ĐẶT THEO THỨ TỰ ƯU TIÊN
Route::get('/products/search', [ProductApiController::class, 'search']); // SEARCH TRƯỚC
Route::get('/products/{id}', [ProductApiController::class, 'show']);
Route::get('/products/slug/{slug}', [ProductApiController::class, 'getProductBySlug']);
Route::get('/products', [ProductApiController::class, 'index']); // INDEX SAU

// Categories
Route::get('/categories', [CategoryApiController::class, 'index']);
Route::post('/categories', [CategoryApiController::class, 'store']);
Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);

// Attributes
Route::get('/attributes', [ProductAttributeApiController::class, 'index']);
Route::post('/attributes', [ProductAttributeApiController::class, 'store']);
Route::get('/attributes/{id}', [ProductAttributeApiController::class, 'show']);
Route::put('/attributes/{id}', [ProductAttributeApiController::class, 'update']);
Route::delete('/attributes/{id}', [ProductAttributeApiController::class, 'destroy']);

// Values
Route::get('/values', [ProductValueApiController::class, 'index']);
Route::get('/values/{id}', [ProductValueApiController::class, 'show']);
Route::post('/values', [ProductValueApiController::class, 'store']);
Route::put('/values/{id}', [ProductValueApiController::class, 'update']);
Route::delete('/values/{id}', [ProductValueApiController::class, 'destroy']);

// Users - PUBLIC routes only
Route::get('/users', [UserApiController::class, 'index']); // admin only, but defined public for now
Route::get('/users/{id}', [UserApiController::class, 'show']); // public user profile
Route::post('/users', [UserApiController::class, 'store']); // registration
Route::delete('/users/{id}', [UserApiController::class, 'destroy']); // admin only

// Authentication Routes
Route::post('/login', [UserApiController::class, 'login']);
Route::post('/admin/login', [UserApiController::class, 'adminLogin']);
Route::post('/register', [RegisterController::class, 'register']);

// JWT Protected Routes
Route::middleware('jwt.auth')->group(function () {
    Route::post('/logout', [UserApiController::class, 'logout']);
    Route::get('/me', [UserApiController::class, 'me']);
});

// Vouchers, Posts, Courts, Orders, etc.
Route::resource('vouchers', VoucherApiController::class);
Route::resource('post_categories', PostCategoryApiController::class);
Route::resource('posts', PostApiController::class);
Route::resource('product_reviews', ProductReviewApiController::class);
Route::resource('comments', PostCommentApi::class);
Route::resource('courts', CourtApi::class);
Route::resource('court_bookings', CourtBookingApi::class);
Route::resource('carts', CartApi::class);
Route::resource('order_details', OrderDetailApi::class);
// Route cụ thể phải đặt TRƯỚC apiResource
Route::get('/orders/check-purchased', [OrderApi::class, 'checkPurchased']);
Route::get('/orders/user/{id}', [OrderApi::class, 'getOrdersByUser']);
Route::apiResource('orders', OrderApi::class);
Route::apiResource('roles', RolesApiController::class);

// Banner API
Route::get('banners', [BannerApiController::class, 'index']);

// Flash Sale (Admin)
Route::prefix('admin')->group(function () {
    Route::apiResource('flash-sales', \App\Http\Controllers\Admin\FlashSaleController::class);
});
Route::get('flash-sales', [\App\Http\Controllers\Api\FlashSaleApi::class, 'index']);

// Admin Banner
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banner', BannerController::class);
    Route::get('banner-image/{id}', [BannerController::class, 'image'])->name('banner.image');
});

// Popup
Route::get('popup', [PopupApiController::class, 'index']);

// ChatBot
Route::post('/chatbot/badminton', [ChatBotController::class, 'chatBadminton']);

// Product Variants
Route::get('/product-variants', [ProductVariantController::class, 'index']);

// Orders - chuyển thành public
Route::post('/orders', [OrderApi::class, 'store']);
Route::get('/orders/user/{id}', [OrderApi::class, 'getOrdersByUser']);
Route::post('/orders/{id}/cancel', [OrderApi::class, 'cancelOrder']);

// Comments - chuyển thành public  
Route::post('products/{product}/comments', [CommentApiController::class, 'storeProductComment']);
Route::post('posts/{post}/comments', [CommentApiController::class, 'storePostComment']);

// Ratings - chuyển thành public
Route::post('/products/{productId}/ratings', [ProductRatingController::class, 'store']);

// Notifications - chuyển thành public
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/unread/{user_id?}', [NotificationController::class, 'unread']);
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/mark-read', [NotificationController::class, 'markManyAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
Route::post('/notifications/delete-many', [NotificationController::class, 'destroyMany']);
Route::post('/notifications/read-all', [NotificationController::class, 'readAll']);
Route::post('/notifications', [NotificationController::class, 'store']);

// Court Bookings - chuyển thành public
Route::post('/court_bookings', [CourtBookingApi::class, 'store']);
Route::post('/court-bookings/{id}/cancel', [CourtBookingApi::class, 'cancel']);
Route::get('court_bookings/user/{id}', [CourtBookingApi::class, 'getByUser']);

// Comment Rating - chuyển thành public
Route::post('comments/{id}/rate', [CommentRatingApiController::class, 'store']);

// Payment - chuyển thành public
Route::post('/vnpay/create', [VnpayController::class, 'createPayment']);

// User profile updates - chuyển thành public
Route::put('/users/{id}', [UserApiController::class, 'update']);
Route::post('/users/{id}', [UserApiController::class, 'update']);
Route::post('/users/{id}/update-profile', [UserApiController::class, 'updateProfile']);

// Public API Routes (không cần authentication)
Route::post('/vouchers/check', [VoucherApiController::class, 'check']);

// Locations
Route::get('/locations', [LocationApi::class, 'index']);
Route::get('/locations/{id}/courts', [LocationApi::class, 'courts']);

// Bình luận sản phẩm - GET public, POST protected
Route::get('products/{product}/comments', [CommentApiController::class, 'productComments']);

// Bình luận bài viết - GET public, POST protected  
Route::get('posts/{post}/comments', [CommentApiController::class, 'postComments']);

// Đánh giá bình luận (like/dislike) - GET public, POST protected
Route::get('comments/{id}/rate', [CommentRatingApiController::class, 'count']);

// Đánh giá sản phẩm (rating) - GET public, POST protected
Route::get('/products/{productId}/ratings', [ProductRatingController::class, 'list']);

// LẤY TOP ĐÁNH GIÁ CAO NHẤT TOÀN SHOP
Route::get('/top-reviews', [ProductRatingController::class, 'topReviews']);

// Contact Messages - POST public để khách có thể liên hệ
Route::post('/contact', [ContactMessageController::class, 'store']);
Route::get('/contacts', [ContactMessageController::class, 'index']); // (admin only)

// Expert Reviews - PUBLIC
Route::get('/expert-reviews', [\App\Http\Controllers\Api\ExpertReviewApiController::class, 'index']);


Route::prefix('product-lines')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\ProductLineApiController::class, 'index']);
    Route::get('/brand/{brand}', [App\Http\Controllers\Api\ProductLineApiController::class, 'getByBrand']);
    Route::get('/{id}', [App\Http\Controllers\Api\ProductLineApiController::class, 'show']);
});

// Promotions API
Route::prefix('promotions')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\PromotionController::class, 'apiIndex']);
    Route::get('/{promotion}', [\App\Http\Controllers\Admin\PromotionController::class, 'apiShow']);
    Route::post('/validate-code', [\App\Http\Controllers\Admin\PromotionController::class, 'validatePromoCode']);
});

// Admin Comment Management Routes (Protected)
Route::middleware(['jwt.auth', 'admin'])->prefix('admin/comments')->group(function () {
    // Product Comments
    Route::get('products', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'getProductComments']);
    Route::put('products/{id}/status', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'updateProductCommentStatus']);
    Route::delete('products/{id}', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'deleteProductComment']);
    Route::post('products/bulk-update', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'bulkUpdateProductComments']);
    Route::delete('products/bulk-delete', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'bulkDeleteProductComments']);
    
    // Post Comments
    Route::get('posts', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'getPostComments']);
    Route::delete('posts/{id}', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'deletePostComment']);
    Route::delete('posts/bulk-delete', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'bulkDeletePostComments']);
    
    // Stats & Filters
    Route::get('stats', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'getCommentStats']);
    Route::get('products-filter', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'getProductsForFilter']);
    Route::get('posts-filter', [\App\Http\Controllers\Api\Admin\CommentManagementController::class, 'getPostsForFilter']);
});