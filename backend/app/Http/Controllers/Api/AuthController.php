<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'guard_name' => 'required|string|in:platform,supplier,distributor',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->where('guard_name', $request->guard_name)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['凭证无效或角色不匹配'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['账号已被禁用'],
            ]);
        }

        $token = $user->createToken($request->guard_name)->plainTextToken;

        return response()->json([
            'code' => 0,
            'data' => [
                'token' => $token,
                'user' => $user->load('roles.permissions'),
            ],
            'message' => '登录成功',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'code' => 0,
            'data' => null,
            'message' => '退出成功',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user()->load('roles.permissions');

        return response()->json([
            'code' => 0,
            'data' => $user,
            'message' => 'success',
        ]);
    }
}
