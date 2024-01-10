<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;

class AssignPermissionsToRoleService
{
    public function assignPermissionsToRole($data)
    {
        $role = Role::findOrFail($data['role']);
        $permissions = Permission::find($data['permissions'])->pluck('name')->toArray();
        $role->syncPermissions($permissions);
        return true;
    }
}
