<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Tạo thông báo mới
     */
    public static function create($userId, $title, $message, $type = 'info', $link = null, $icon = null, $priority = 'normal')
    {
        return Notification::create([
            'User_ID' => $userId,
            'Title' => $title,
            'Message' => $message,
            'Type' => $type,
            'is_read' => false,
            'link' => $link,
            'icon' => $icon,
            'priority' => $priority
        ]);
    }

    /**
     * Thông báo đặt hàng thành công
     */
    public static function orderCreated($userId, $orderCode, $totalAmount)
    {
        return self::create(
            $userId,
            '🎉 Đặt hàng thành công!',
            "Đơn hàng #{$orderCode} của bạn đã được tạo thành công với tổng giá trị " . number_format($totalAmount) . "đ. Chúng tôi sẽ xử lý và giao hàng sớm nhất.",
            'success',
            "/profile?tab=orders",
            'fas fa-check-circle',
            'high'
        );
    }

    /**
     * Thông báo xác nhận đơn hàng
     */
    public static function orderConfirmed($userId, $orderCode)
    {
        return self::create(
            $userId,
            '✅ Đơn hàng được xác nhận',
            "Đơn hàng #{$orderCode} đã được xác nhận và đang chuẩn bị hàng. Bạn sẽ nhận được thông báo khi hàng được giao.",
            'info',
            "/profile?tab=orders",
            'fas fa-clipboard-check',
            'high'
        );
    }

    /**
     * Thông báo đang giao hàng
     */
    public static function orderShipping($userId, $orderCode)
    {
        return self::create(
            $userId,
            '🚚 Đơn hàng đang giao',
            "Đơn hàng #{$orderCode} đang trên đường giao đến bạn. Vui lòng chuẩn bị nhận hàng.",
            'info',
            "/profile?tab=orders",
            'fas fa-truck',
            'high'
        );
    }

    /**
     * Thông báo giao hàng thành công
     */
    public static function orderDelivered($userId, $orderCode)
    {
        return self::create(
            $userId,
            '🎁 Giao hàng thành công!',
            "Đơn hàng #{$orderCode} đã được giao thành công. Cảm ơn bạn đã mua hàng! Hãy đánh giá sản phẩm để chia sẻ trải nghiệm.",
            'success',
            "/profile?tab=orders",
            'fas fa-gift',
            'high'
        );
    }

    /**
     * Thông báo hủy đơn hàng
     */
    public static function orderCancelled($userId, $orderCode, $reason = '')
    {
        $message = "Đơn hàng #{$orderCode} đã bị hủy";
        if ($reason) {
            $message .= " với lý do: {$reason}";
        }
        $message .= ". Nếu có thắc mắc, vui lòng liên hệ hotline để được hỗ trợ.";

        return self::create(
            $userId,
            '❌ Đơn hàng bị hủy',
            $message,
            'warning',
            "/profile?tab=orders",
            'fas fa-times-circle',
            'high'
        );
    }

    /**
     * Thông báo đặt sân thành công
     */
    public static function bookingCreated($userId, $bookingCode, $courtName, $bookingDate)
    {
        return self::create(
            $userId,
            '🏸 Đặt sân thành công!',
            "Bạn đã đặt sân {$courtName} thành công với mã đặt sân #{$bookingCode} vào ngày {$bookingDate}. Hãy đến đúng giờ để trải nghiệm tốt nhất!",
            'success',
            "/profile?tab=bookings",
            'fas fa-calendar-check',
            'high'
        );
    }

    /**
     * Thông báo xác nhận đặt sân
     */
    public static function bookingConfirmed($userId, $bookingCode)
    {
        return self::create(
            $userId,
            '✅ Đặt sân được xác nhận',
            "Đặt sân #{$bookingCode} đã được xác nhận. Vui lòng đến đúng giờ và mang theo giấy tờ tùy thân.",
            'info',
            "/profile?tab=bookings",
            'fas fa-clipboard-check',
            'high'
        );
    }

    /**
     * Thông báo hủy đặt sân
     */
    public static function bookingCancelled($userId, $bookingCode, $reason = '')
    {
        $message = "Đặt sân #{$bookingCode} đã bị hủy";
        if ($reason) {
            $message .= " với lý do: {$reason}";
        }
        $message .= ". Nếu có thắc mắc, vui lòng liên hệ hotline.";

        return self::create(
            $userId,
            '❌ Đặt sân bị hủy',
            $message,
            'warning',
            "/profile?tab=bookings",
            'fas fa-times-circle',
            'high'
        );
    }

    /**
     * Thông báo khuyến mãi mới
     */
    public static function newPromotion($userId, $promotionTitle, $discountValue, $promotionCode = null)
    {
        $message = "Chương trình khuyến mãi '{$promotionTitle}' với giá trị giảm {$discountValue} đã có hiệu lực!";
        if ($promotionCode) {
            $message .= " Sử dụng mã: {$promotionCode}";
        }
        $message .= " Nhanh tay mua sắm để không bỏ lỡ ưu đãi!";

        return self::create(
            $userId,
            '🎊 Khuyến mãi mới!',
            $message,
            'promotion',
            "/promotions",
            'fas fa-gift',
            'normal'
        );
    }

    /**
     * Thông báo sản phẩm yêu thích có khuyến mãi
     */
    public static function favoriteProductOnSale($userId, $productName, $discountPercent)
    {
        return self::create(
            $userId,
            '💖 Sản phẩm yêu thích giảm giá!',
            "Sản phẩm '{$productName}' trong danh sách yêu thích của bạn đang có khuyến mãi {$discountPercent}%! Đừng bỏ lỡ cơ hội này.",
            'favorite',
            "/profile?tab=favorites",
            'fas fa-heart',
            'normal'
        );
    }

    /**
     * Thông báo bình luận được phê duyệt
     */
    public static function commentApproved($userId, $productName)
    {
        return self::create(
            $userId,
            '💬 Bình luận được duyệt',
            "Bình luận của bạn về sản phẩm '{$productName}' đã được phê duyệt và xuất hiện trên trang sản phẩm.",
            'info',
            null,
            'fas fa-comment',
            'low'
        );
    }

    /**
     * Thông báo sinh nhật - tặng voucher
     */
    public static function birthdayVoucher($userId, $voucherCode, $discountValue)
    {
        return self::create(
            $userId,
            '🎂 Chúc mừng sinh nhật!',
            "Chúc mừng sinh nhật bạn! Chúng tôi tặng bạn voucher giảm giá {$discountValue}% với mã: {$voucherCode}. Hãy sử dụng trong vòng 30 ngày!",
            'birthday',
            "/promotions",
            'fas fa-birthday-cake',
            'high'
        );
    }

    /**
     * Thông báo tích điểm thành viên
     */
    public static function pointsEarned($userId, $points, $totalPoints)
    {
        return self::create(
            $userId,
            '⭐ Nhận điểm thưởng!',
            "Bạn vừa nhận được {$points} điểm từ đơn hàng gần đây. Tổng điểm hiện tại: {$totalPoints}. Tích đủ điểm để đổi voucher hấp dẫn!",
            'points',
            "/profile?tab=points",
            'fas fa-star',
            'normal'
        );
    }

    /**
     * Thông báo sản phẩm sắp hết hàng (cho admin thông báo user quan tâm)
     */
    public static function productLowStock($userId, $productName)
    {
        return self::create(
            $userId,
            '⚠️ Sản phẩm sắp hết hàng!',
            "Sản phẩm '{$productName}' mà bạn quan tâm chỉ còn ít hàng trong kho. Nhanh tay đặt hàng để không bỏ lỡ!",
            'warning',
            "/products",
            'fas fa-exclamation-triangle',
            'normal'
        );
    }

    /**
     * Thông báo cập nhật thông tin tài khoản thành công
     */
    public static function profileUpdated($userId)
    {
        return self::create(
            $userId,
            '✅ Cập nhật thành công',
            "Thông tin tài khoản của bạn đã được cập nhật thành công. Cảm ơn bạn đã cập nhật thông tin để chúng tôi phục vụ tốt hơn!",
            'success',
            "/profile",
            'fas fa-user-check',
            'low'
        );
    }

    /**
     * Thông báo đổi mật khẩu thành công
     */
    public static function passwordChanged($userId)
    {
        return self::create(
            $userId,
            '🔒 Đổi mật khẩu thành công',
            "Mật khẩu của bạn đã được thay đổi thành công. Nếu không phải bạn thực hiện, vui lòng liên hệ ngay với chúng tôi!",
            'security',
            null,
            'fas fa-shield-alt',
            'high'
        );
    }

    /**
     * Gửi thông báo cho nhiều user
     */
    public static function broadcast($userIds, $title, $message, $type = 'info', $link = null, $icon = null, $priority = 'normal')
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'User_ID' => $userId,
                'Title' => $title,
                'Message' => $message,
                'Type' => $type,
                'is_read' => false,
                'link' => $link,
                'icon' => $icon,
                'priority' => $priority,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        return Notification::insert($notifications);
    }

    /**
     * Đánh dấu đã đọc
     */
    public static function markAsRead($notificationId)
    {
        return Notification::where('Notifications_ID', $notificationId)
            ->update(['is_read' => true]);
    }

    /**
     * Đánh dấu tất cả đã đọc
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('User_ID', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Xóa thông báo cũ (quá 30 ngày)
     */
    public static function cleanOldNotifications()
    {
        return Notification::where('created_at', '<', now()->subDays(30))->delete();
    }
}