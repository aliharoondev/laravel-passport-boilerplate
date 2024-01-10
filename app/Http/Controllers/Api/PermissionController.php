<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{

    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Permissions fetched successfully',
            'data' => PermissionResource::collection($this->permissionService->getAllPermissions())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $this->permissionService->storePermission($request->all());
            return response()->json([
                'message' => 'Permission created successfully',
                'data' => $request->all()
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json([
                'message' => 'Permission fetched successfully',
                'data' => PermissionResource::make($this->permissionService->getPermissionById($id))
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
    public function update(UpdatePermissionRequest $request, string $id)
    {
        try {
            $this->permissionService->updatePermission($request->all(), $id);
            return response()->json([
                'message' => 'Permission updated successfully',
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
            $this->permissionService->deletePermission($id);
            return response()->json([
                'message' => 'Permission deleted successfully',
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
