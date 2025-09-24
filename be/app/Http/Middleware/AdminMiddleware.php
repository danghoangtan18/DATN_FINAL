<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sử dụng JWT auth guard
        $user = auth('api')->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized - Token required'], 401);
        }

        // Kiểm tra quyền admin (Role_ID = 1 là admin, 2 là staff)
        if ($user->Role_ID !== \App\Models\Role::ADMIN && $user->Role_ID !== \App\Models\Role::STAFF) {
            return response()->json([
                'message' => 'Forbidden - Bạn không có quyền truy cập tính năng này',
                'required_role' => 'admin_or_staff',
                'your_role_id' => $user->Role_ID
            ], 403);
        }

        return $next($request);
    }
}
