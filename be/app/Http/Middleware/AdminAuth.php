<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra admin session
        if (!session('admin_logged_in') || !session('admin_user')) {
            return redirect()->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập để truy cập trang admin!');
        }

        $adminUser = session('admin_user');
        
        // Kiểm tra quyền admin/staff
        if (!in_array($adminUser['Role_ID'], [1, 2])) {
            session()->flush(); // Xóa session không hợp lệ
            return redirect()->route('admin.login')
                ->with('error', 'Bạn không có quyền truy cập khu vực admin!');
        }

        return $next($request);
    }
}