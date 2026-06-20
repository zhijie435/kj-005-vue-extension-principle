<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Role::with('permissions');

        if ($request->filled('guard_name')) {
            $query->where('guard_name', $request->guard_name);
        }

        $roles = $query->get();

        return response()->json([
            'code' => 0,
            'data' => $roles,
            'message' => 'success',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'display_name' => 'nullable|string',
            'guard_name' => 'required|string|in:platform,supplier,distributor',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'display_name' => $request->display_name,
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)
                ->where('guard_name', $request->guard_name)
                ->get();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'code' => 0,
            'data' => $role->load('permissions'),
            'message' => '创建成功',
        ]);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'data' => $role->load('permissions'),
            'message' => 'success',
        ]);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $request->validate([
            'name' => 'string|unique:roles,name,' . $role->id,
            'display_name' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($request->has('name')) {
            $role->name = $request->name;
        }

        if ($request->has('display_name')) {
            $role->display_name = $request->display_name;
        }

        if ($request->has('name') || $request->has('display_name')) {
            $role->save();
        }

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)
                ->where('guard_name', $role->guard_name)
                ->get();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'code' => 0,
            'data' => $role->load('permissions'),
            'message' => '更新成功',
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        if (in_array($role->name, ['platform', 'supplier', 'distributor'])) {
            return response()->json([
                'code' => 1,
                'data' => null,
                'message' => '系统角色不可删除',
            ], 403);
        }

        $role->delete();

        return response()->json([
            'code' => 0,
            'data' => null,
            'message' => '删除成功',
        ]);
    }

    public function permissions(Request $request): JsonResponse
    {
        $query = Permission::query();

        if ($request->filled('guard_name')) {
            $query->where('guard_name', $request->guard_name);
        }

        $permissions = $query->orderBy('name')->get();

        $grouped = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0];
        });

        return response()->json([
            'code' => 0,
            'data' => [
                'permissions' => $permissions,
                'grouped' => $grouped,
            ],
            'message' => 'success',
        ]);
    }
}
