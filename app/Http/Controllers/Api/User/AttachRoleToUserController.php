<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AssignRoleToUserRequest;
use App\Services\AttachRoleToUserService;

class AttachRoleToUserController extends Controller
{
    protected $attachRoleToUserService;

    public function __construct(AttachRoleToUserService $attachRoleToUserService)
    {
        $this->attachRoleToUserService = $attachRoleToUserService;
    }

    public function store(AssignRoleToUserRequest $attachRoleToUserService)
    {

        try {
            $this->attachRoleToUserService->assignRoleToUser($attachRoleToUserService->all());
            return response()->json([
                'message' => 'Roles assigned successfully',
                'data' => $attachRoleToUserService->all()
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => []
            ], 400);
        }
    }
}
