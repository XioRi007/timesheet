<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkLogRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'developer_id' => 'numeric',
            'project_id' => 'numeric',
            'rate' => 'numeric|between:0,999.99',
            'hrs' => 'numeric|between:0,999.99',
            'total' => 'numeric|between:0,99999999.99',
            'status' => 'boolean',
            'date' => 'required|date'
        ];
    }
}
