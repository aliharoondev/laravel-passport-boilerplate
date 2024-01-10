<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class AssignRoleToUserRequest extends FormRequest
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
            'user' => ['required', 'integer', 'exists:users,id', Rule::exists('users', 'id')->where(function ($query) {
                $query->where('is_active', 1);
            })],
            'roles' => ['required', 'array', 'max:2'],
            'roles.*' => ['required', 'integer', 'exists:roles,id', Rule::exists('roles', 'id')->where(function ($query) {
                $query->where('is_active', 1);
            })],
        ];
    }

    public function messages()
    {
        return [
            'roles.*' => 'The selected role :input is invalid.',
        ];
    }
}
