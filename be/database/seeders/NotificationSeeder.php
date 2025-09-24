<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\NotificationService;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Lấy một số user để test
        $users = User::limit(5)->get();

        foreach ($users as $user) {
            // Thông báo chào mừng
            NotificationService::create(
                $user->ID,
                '🎉 Chào mừng đến với Vicnex!',
                'Cảm ơn bạn đã đăng ký tài khoản tại Vicnex. Khám phá ngay các sản phẩm cầu lông chất lượng cao với giá tốt nhất!',
                'welcome',
                '/products',
                'fas fa-handshake',
                'normal'
            );

            // Thông báo khuyến mãi
            NotificationService::newPromotion(
                $user->ID,
                'Flash Sale Cuối Tuần',
                '50%',
                'FLASHSALE50'
            );

            // Thông báo tích điểm
            NotificationService::pointsEarned(
                $user->ID,
                100,
                500
            );
        }
    }
}