<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;

class UserApiController extends Controller
{
    // ✅ GET /api/users
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // ✅ GET /api/users/{id}
    public function show($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    // ✅ POST /api/users (Đăng ký)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Role_ID'       => 'required|exists:roles,Role_ID',
            'Name'          => 'required|string|max:255',
            'Email'         => 'required|email|unique:user,Email',
            'Password'      => 'required|string|min:6',
            'Phone'         => 'nullable|string|max:20',
            'Gender'        => 'nullable|in:male,female,0,1',
            'Date_of_birth' => 'nullable|date',
            'Avatar'        => 'nullable|string',
            'Status'        => 'nullable|boolean',
            'Address'       => 'nullable|string|max:500',
            'ward'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'province'      => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'Role_ID'       => $request->Role_ID,
            'Name'          => $request->Name,
            'Email'         => $request->Email,
            'Password'      => Hash::make($request->Password),
            'Phone'         => $request->Phone,
            'Gender'        => $request->Gender,
            'Date_of_birth' => $request->Date_of_birth,
            'Avatar'        => $request->Avatar,
            'Status'        => $request->Status ?? 1,
            'Address'       => $request->Address,
            'ward'          => $request->ward,
            'district'      => $request->district,
            'province'      => $request->province,
        ]);

        return response()->json($user, 201);
    }

    // ✅ PUT /api/users/{id} (Cập nhật)
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'Name' => 'sometimes|required|string|max:255',
            'Email' => "sometimes|required|email|unique:user,Email,{$id},ID",
            'Password' => 'sometimes|nullable|string|min:6',
            'Phone' => 'nullable|string|max:20',
            'Gender' => 'nullable|in:male,female,0,1',
            'Date_of_birth' => 'nullable|date',
            'Avatar' => 'nullable|file|image|max:2048',
            'Status' => 'nullable|boolean',
            'Address' => 'nullable|string|max:500',
            'ward'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'province'      => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Xử lý upload file Avatar nếu có
        if ($request->hasFile('Avatar')) {
            if ($user->Avatar && file_exists(public_path($user->Avatar))) {
                @unlink(public_path($user->Avatar));
            }
            $file = $request->file('Avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/users'), $filename);
            $user->Avatar = 'uploads/users/' . $filename;
        }

        foreach (['Name','Email','Phone','Gender','Date_of_birth','Status','Address','ward','district','province'] as $key) {
            if ($request->has($key)) {
                $user->$key = $request->$key;
            }
        }
        if ($request->filled('Password')) {
            $user->Password = Hash::make($request->Password);
        }

        $user->save();
        
        // Gửi thông báo cập nhật profile thành công
        NotificationService::profileUpdated($user->ID);

        return response()->json($user, 200);
    }

    // ✅ DELETE /api/users/{id}
    public function destroy($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }

    // ✅ POST /api/login (Đăng nhập)
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('Email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->Password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        // Tạo JWT token
        $token = auth('api')->login($user);

        // Load role information
        $user->load('role');

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user'    => $user,
            'token'   => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 200);
    }

    // ✅ POST /api/admin/login (Đăng nhập admin)
    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('Email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->Password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        // Kiểm tra quyền admin
        if ($user->Role_ID !== \App\Models\Role::ADMIN) {
            return response()->json(['message' => 'Bạn không có quyền truy cập admin'], 403);
        }

        // Tạo JWT token
        $token = auth('api')->login($user);

        // Load role information
        $user->load('role');

        return response()->json([
            'message' => 'Đăng nhập admin thành công',
            'user'    => $user,
            'token'   => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'role'    => $user->role->Name ?? 'Admin'
        ], 200);
    }

    // ✅ POST /api/logout (Đăng xuất)
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Đăng xuất thành công'], 200);
    }

    // ✅ GET /api/me (Lấy thông tin user hiện tại)
    public function me()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->load('role');
        return response()->json($user);
    }
}
