<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'unique:' . User::class],
            'password' => [
                'required', Password::defaults(),
            ],
        ];
    }
}
