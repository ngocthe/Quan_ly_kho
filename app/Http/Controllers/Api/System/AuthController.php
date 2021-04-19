<?php

namespace App\Http\Controllers\Api\System;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\MeResource;
use App\Models\System\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login', "createToken"]]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Thông tin đăng nhập không hợp lệ'], 401);
        }
        $user = Auth::user();
        if (!$user->active) {
            return Response::error('Tài khoản chưa được kích hoạt');
        }
        return ['message' => 'Đăng nhập thành công', 'home_url' => $user->role->home_url];
    }
    public function createToken(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return Response::error('Invalid credentials');
        }

        return ['access_token' => $user->createToken('access_token')->plainTextToken];
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        if ($request->headers->get('Authorization')) return Auth::user();
        return new MeResource(Auth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('web')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }



    public function changePassword(ChangePasswordRequest $request)
    {
        if (!Hash::check($request->old_password, Auth::user()->password))
            return Response::error('Mật khẩu cũ không đúng');
        Auth::user()->update(['password' => Hash::make($request->password)]);
        return ['message' => 'OK'];
    }
}
