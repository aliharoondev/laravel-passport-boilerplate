<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    protected $roleSerive;

    public function __construct(RoleService $roleSerive)
    {
        $this->roleSerive = $roleSerive;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Roles fetched successfully',
            'data' => RoleResource::collection($this->roleSerive->getAllRoles())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $this->roleSerive->createRole($request->all());
            return response()->json([
                'message' => 'Role created successfully',
                'data' => $request->all()
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json([
                'message' => 'Role fetched successfully',
                'data' => new RoleResource($this->roleSerive->getRoleById($id))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        try {
            $this->roleSerive->updateRole($request->all(), $id);
            return response()->json([
                'message' => 'Role updated successfully',
                'data' => $request->all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->roleSerive->deleteRole($id);
            return response()->json([
                'message' => 'Role deleted successfully',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }
}
