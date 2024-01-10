<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class AssignPermissionsToRoleRequest extends FormRequest
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
            'role' => ['required', 'integer', 'exists:roles,id'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id', Rule::exists('permissions', 'id')->where(function ($query) {
                $query->where('is_active', 1);
            })],
        ];
    }

    public function messages()
    {
        return [
            'permissions.*' => 'The selected permission :input is invalid.',
        ];
    }
}
