<?php

namespace App\Http\Requests\Filters;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFilterRequest extends FormRequest
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
            'type' => 'nullable|string|in:income,expense',
            'sort_by' => 'nullable|string|in:created_at,name',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|numeric',
            'page' => 'nullable|numeric'
        ];
    }
}
