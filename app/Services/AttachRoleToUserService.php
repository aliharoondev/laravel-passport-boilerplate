<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;

class AttachRoleToUserService
{
    public function assignRoleToUser($data)
    {
        $user = User::findOrFail($data['user']);
        $roles = Role::find($data['roles'])->pluck('name')->toArray();
        $user->syncRoles($roles);

        return true;
    }
}
