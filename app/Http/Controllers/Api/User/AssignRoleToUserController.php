<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AssignRoleToUserRequest;
use App\Services\AssignRoleToUserService;

class AssignRoleToUserController extends Controller
{
    protected $assignRoleToUserService;

    public function __construct(AssignRoleToUserService $assignRoleToUserService)
    {
        $this->assignRoleToUserService = $assignRoleToUserService;
    }

    public function store(AssignRoleToUserRequest $assignRoleToUserRequest)
    {

        try {
            $this->assignRoleToUserService->assignRoleToUser($assignRoleToUserRequest->all());
            return response()->json([
                'message' => 'Roles assigned successfully',
                'data' => $assignRoleToUserRequest->all()
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => []
            ], 400);
        }
    }
}
