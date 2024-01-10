<?php

namespace App\Services;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PermissionService
{
    public function getAllPermissions()
    {
        return Permission::select('id', 'name', 'slug', 'is_active')->get();
    }

    public function storePermission($data)
    {
        $permission = new Permission();
        $permission = $this->makePermissionData($permission, $data);
        $permission->created_by = Auth::id();
        return $permission->save();
    }

    public function getPermissionById($id)
    {
        return Permission::findOrFail($id);
    }

    public function updatePermission($data, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission = $this->makePermissionData($permission, $data);
        $permission->updated_by = Auth::id();
        return $permission->save();
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->deleted_by = Auth::id();
        $permission->deleted_at = Carbon::now();
        return $permission->save();
    }

    public function makePermissionData($permission, $data)
    {
        $permission->name = $data['name'];
        $permission->guard_name = $data['guard_name'];
        $permission->slug = Str::slug($data['name']);
        $permission->is_active = $data['active'];
        return $permission;
    }
}
