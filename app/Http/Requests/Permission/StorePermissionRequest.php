<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\FormRequest;

class StorePermissionRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'active' => ['required', 'boolean', 'in:0,1'],
            'guard_name' => ['required', 'string', 'in:api,web'],
        ];
    }
}
