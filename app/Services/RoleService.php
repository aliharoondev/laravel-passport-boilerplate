<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RoleService
{
    public function getAllRoles()
    {
        return Role::select('id', 'name', 'guard_name')->get();
    }

    public function createRole($data)
    {
        $role = new Role();
        $role = $this->makeRoleData($role, $data);
        $role->created_by = Auth::id();
        return $role->save();
    }

    public function getRoleById($id)
    {
        return Role::findOrFail($id);
    }

    public function updateRole($data, $id)
    {
        $role = Role::findOrFail($id);
        $role = $this->makeRoleData($role, $data);
        $role->updated_by = Auth::id();
        return $role->save();
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->deleted_at = Carbon::now();
        $role->deleted_by = Auth::id();
        return $role->save();
    }

    public function makeRoleData($role, $data)
    {
        $role->name = $data['name'];
        $role->is_active = $data['active'];
        $role->guard_name = $data['guard_name'];
        if (!$role->id) {
            $role->slug = Str::slug($data['name']);
        }

        return $role;
    }
}
