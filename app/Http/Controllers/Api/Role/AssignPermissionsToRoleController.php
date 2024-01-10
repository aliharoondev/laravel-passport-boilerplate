<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Services\AssignPermissionsToRoleService;
use App\Http\Requests\Role\AssignPermissionsToRoleRequest;

class AssignPermissionsToRoleController extends Controller
{

    protected $assignPermissionsToRoleService;

    public function __construct(AssignPermissionsToRoleService $assignPermissionsToRoleService)
    {
        $this->assignPermissionsToRoleService = $assignPermissionsToRoleService;
    }

    public function store(AssignPermissionsToRoleRequest $request)
    {
        try {
            $this->assignPermissionsToRoleService->assignPermissionsToRole($request->all());
            return response()->json([
                'message' => 'Permissions assigned successfully',
                'data' => $request->all()
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => []
            ], 400);
        }
    }
}
