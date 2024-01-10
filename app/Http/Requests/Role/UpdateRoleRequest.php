<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:45', Rule::unique('roles')->ignore($this->role)],
            'active' => ['required', 'boolean', 'in:0,1'],
            'guard_name' => ['required', 'string', 'in:api,web'],
        ];
    }
}
