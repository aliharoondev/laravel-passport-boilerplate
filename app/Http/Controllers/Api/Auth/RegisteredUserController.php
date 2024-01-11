<?php

namespace App\Http\Controllers\API\Auth;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegistrationRequest;
use App\Events\UserRegistered;
use App\Models\User;

class RegisteredUserController extends Controller
{
    public function store(UserRegistrationRequest $request): JsonResponse
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $authToken = $user->createToken('cuterabackend')->accessToken;

            event(new UserRegistered($user));

            return response()->json([
                'message' => 'Registration completed. A verification email has been sent to your provided email.',
                'data' => $authToken
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }
}
