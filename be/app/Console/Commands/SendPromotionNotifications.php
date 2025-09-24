<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promotion;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendPromotionNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotions:notify {--new : Gửi thông báo cho khuyến mãi mới} {--birthday : Gửi voucher sinh nhật}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi thông báo khuyến mãi cho khách hàng';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('new')) {
            $this->sendNewPromotionNotifications();
        }
        
        if ($this->option('birthday')) {
            $this->sendBirthdayVouchers();
        }
        
        if (!$this->option('new') && !$this->option('birthday')) {
            $this->sendNewPromotionNotifications();
        }
    }

    /**
     * Gửi thông báo khuyến mãi mới
     */
    private function sendNewPromotionNotifications()
    {
        $this->info('Đang tìm khuyến mãi mới...');
        
        // Tìm các khuyến mãi bắt đầu hôm nay
        $newPromotions = Promotion::where('start_date', '>=', Carbon::today())
            ->where('start_date', '<', Carbon::tomorrow())
            ->where('is_active', true)
            ->get();

        if ($newPromotions->isEmpty()) {
            $this->info('Không có khuyến mãi mới nào hôm nay.');
            return;
        }

        // Lấy tất cả user active
        $activeUsers = User::where('Status', 1)->pluck('ID')->toArray();

        foreach ($newPromotions as $promotion) {
            $this->info("Gửi thông báo khuyến mãi: {$promotion->title}");
            
            // Gửi thông báo cho tất cả user
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

        $this->info("Đã gửi thông báo cho " . count($activeUsers) . " khách hàng về " . count($newPromotions) . " khuyến mãi mới.");
    }

    /**
     * Gửi voucher sinh nhật
     */
    private function sendBirthdayVouchers()
    {
        $this->info('Đang tìm khách hàng sinh nhật hôm nay...');
        
        // Tìm user có sinh nhật hôm nay
        $birthdayUsers = User::whereMonth('Date_of_birth', Carbon::now()->month)
            ->whereDay('Date_of_birth', Carbon::now()->day)
            ->where('Status', 1)
            ->get();

        if ($birthdayUsers->isEmpty()) {
            $this->info('Không có khách hàng nào sinh nhật hôm nay.');
            return;
        }

        foreach ($birthdayUsers as $user) {
            $voucherCode = 'BIRTHDAY_' . strtoupper(substr($user->Name, 0, 3)) . '_' . Carbon::now()->format('md');
            
            NotificationService::birthdayVoucher($user->ID, $voucherCode, 15);
            
            $this->info("Đã gửi voucher sinh nhật cho: {$user->Name}");
        }

        $this->info("Đã gửi voucher sinh nhật cho " . count($birthdayUsers) . " khách hàng.");
    }
}
