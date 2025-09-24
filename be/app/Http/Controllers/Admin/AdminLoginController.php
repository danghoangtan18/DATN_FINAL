<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminLoginController extends Controller
{
    public function login(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput();
        }

        // Tìm user theo email
        $user = User::where('Email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->Password)) {
            return redirect()->route('admin.login')
                ->with('error', 'Email hoặc mật khẩu không đúng!')
                ->withInput();
        }

        // Kiểm tra quyền admin/staff
        if (! in_array($user->Role_ID, [1, 2])) {
            return redirect()->route('admin.login')
                ->with('error', 'Bạn không có quyền truy cập khu vực admin!')
                ->withInput();
        }

        // Tạo JWT token cho admin session
        $token = JWTAuth::fromUser($user);
        
        // Lưu thông tin admin vào session
        session([
            'admin_user' => $user->toArray(),
            'admin_token' => $token,
            'admin_logged_in' => true
        ]);

        // Đăng nhập Laravel Auth để tương thích
        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Đăng nhập thành công!');
    }

    public function logout(Request $request)
    {
        // Xóa session admin
        session()->forget(['admin_user', 'admin_token', 'admin_logged_in']);
        
        // Logout Laravel Auth
        Auth::logout();
        
        // Invalidate JWT token nếu có
        try {
            if (session('admin_token')) {
                JWTAuth::setToken(session('admin_token'))->invalidate();
            }
        } catch (\Exception $e) {
            // Token đã hết hạn hoặc không hợp lệ
        }

        return redirect()->route('admin.login')
            ->with('success', 'Đăng xuất thành công!');
    }
}