<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->email_or_phone) {
            $email_or_phone = $this->email_or_phone;
            if (!filter_var($email_or_phone, FILTER_VALIDATE_EMAIL) && !preg_match('/^[0-9\s+\-()]+$/', $email_or_phone)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email_or_phone' => 'required',
            'password' => 'required',
        ];
    }

    protected function failedAuthorization()
    {
        $response = [
            'errors' => [
                'email_or_phone' => ['Enter Valid Email or Phone Number'],
            ],
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
