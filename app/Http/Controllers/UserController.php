<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->first();
            if ($user) {
                if (!$user->status) {
                    Mail::to($user->email)->send(new \App\Mail\ActivationMail($user->activation_token, $user));
                    return response()->json([
                        'success' => false,
                        'message' => 'Tài khoản đã đăng ký, vui lòng vào email để kích hoạt tài khoản',
                    ], 200);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Email đã tồn tại',
                ], 201);
            }
            $token = Str::random(60);
            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'activation_token' => $token,
                'status' => false,
            ]);
            Mail::to($newUser->email)->send(new \App\Mail\ActivationMail($token, $newUser));
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công, vui lòng vào email để kích hoạt tài khoản',
                'user' => $newUser,
                'activation_token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng ký thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ',
            ], 400);
        }
        $user->status = true;
        $user->activation_token = null;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Kích hoạt tài khoản thành công',
        ], 200);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email không tồn tại',
            ], 404);
        }
        if (!password_verify($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không đúng',
            ], 401);
        }
        if (!$user->status) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản chưa được kích hoạt',
            ], 403);
        }
        $user->tokens()->delete();
        // Tạo token mới 
        $token = $user->createToken('personal_access_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'user' => $user,
            'token' => $token
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
        ], 200);
    }
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email không tồn tại',
            ], 404);
        }
        $status = Password::sendResetLink(
            $request->only('email')
        );
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Đã gửi link đặt lại mật khẩu vào email',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi gửi link đặt lại mật khẩu',
        ], 500);
    }
    public function showResetForm($token)
    {
        return redirect()->away("http://localhost:3000/reset-password/$token");
    }
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Đặt lại mật khẩu thành công',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi đặt lại mật khẩu',
        ], 500);
    }
}
