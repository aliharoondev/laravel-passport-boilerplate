<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        if (filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $request->email_or_phone,
                'password' => $request->password
            ];
        } elseif (preg_match('/^[0-9\s+\-()]+$/', $request->email_or_phone)) {
            $credentials = [
                'phone' => $request->email_or_phone,
                'password' => $request->password
            ];
        }

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $this->isEmailVerified($user);
                $token = $user->createToken($user->email)->accessToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'accessToken' => $token,
                    'user' => $user
                ], 200);
            }

            return response()->json([
                'message' => 'Invalid credentials.',
                'data' => []
            ], 400);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Your email is not verified.',
                'data' => []
            ], 409);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function isEmailVerified($user)
    {
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            throw new AuthorizationException('Your email is not verified.');
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Logged out successfully',
            'data' => []
        ]);
    }
}
