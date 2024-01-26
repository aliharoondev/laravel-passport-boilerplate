<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserEmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;


class VerifyEmailController extends Controller
{
    public function verifyAccount(UserEmailVerificationRequest $request)
    {
        try {
            $user = User::find($request->route('id'));

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Account is already verified.', 'data' => $user], 201);

            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
            return response()->json(['message' => 'Account verified successfully', 'data' => $user], 201);


        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found', 'data' => []], 422);
        } catch (DecryptException | \Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'data' => []], 422);
        }
    }
}
