<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::with('roles.permissions');

        if ($request->filled('guard_name')) {
            $query->where('guard_name', $request->guard_name);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                    ->orWhere('email', 'like', "%{$request->keyword}%");
            });
        }

        $users = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'code' => 0,
            'data' => $users,
            'message' => 'success',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'guard_name' => 'required|string|in:platform,supplier,distributor',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'guard_name' => $request->guard_name,
            'is_active' => $request->input('is_active', true),
        ]);

        if ($request->has('roles')) {
            $roles = Role::whereIn('name', $request->roles)
                ->where('guard_name', $request->guard_name)
                ->get();
            $user->syncRoles($roles);
        }

        return response()->json([
            'code' => 0,
            'data' => $user->load('roles.permissions'),
            'message' => '创建成功',
        ]);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'data' => $user->load('roles.permissions'),
            'message' => 'success',
        ]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'string|min:6',
            'guard_name' => 'string|in:platform,supplier,distributor',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $data = $request->only(['name', 'email', 'guard_name', 'is_active']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->has('roles')) {
            $guardName = $request->input('guard_name', $user->guard_name);
            $roles = Role::whereIn('name', $request->roles)
                ->where('guard_name', $guardName)
                ->get();
            $user->syncRoles($roles);
        }

        return response()->json([
            'code' => 0,
            'data' => $user->load('roles.permissions'),
            'message' => '更新成功',
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'code' => 0,
            'data' => null,
            'message' => '删除成功',
        ]);
    }
}
