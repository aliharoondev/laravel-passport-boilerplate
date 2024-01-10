<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    protected function failedValidation(Validator $validator):void
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['errors' => $errors], 422)
        );

        parent::failedValidation($validator);
    }
}
