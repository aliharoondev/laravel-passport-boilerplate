<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::withTrashed()->where('email', $request->email)->first();
        if (!$user->is_active || $user->deleted_at != null) {
            return response()->json([
                'message' => 'Your account is disabled. Please contact your administrator',
                'data' => []
            ], 409);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'message' => 'Reset link sent to your email',
                'data' => []
            ], 200)
            : response()->json([
                'message' => $status,
                'data' => []
            ], 422);
    }
}
