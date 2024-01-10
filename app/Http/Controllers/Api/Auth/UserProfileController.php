<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'User profile',
            'data' => UserResource::make(Auth::user()),
        ]);
    }
}
