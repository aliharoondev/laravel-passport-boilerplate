<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserEmailVerificationRequest extends FormRequest
{
    public function authorize()
    {
        $user = User::find($this->route('id'));
        if (!hash_equals((string) $user->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    protected function failedAuthorization()
    {
        $response = [
            'error' => '403 Forbidden',
            'message' => 'You are not authorized to perform this action.',
        ];

        throw new HttpResponseException(response()->json($response, Response::HTTP_FORBIDDEN));
    }
}
