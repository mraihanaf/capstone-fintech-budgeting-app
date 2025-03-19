<?php

namespace App\Http\Requests\Filters;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFilterRequest extends FormRequest
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
            'category_id' => 'nullable|numeric',
            'type' => 'nullable|string|in:income,expense',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|max_digits:15',
            'is_recurring' => 'nullable|boolean',
            'sort_by' => 'nullable|string|in:amount,transaction_date',
            'sort_order' => 'nullable|string|in:asc,desc',
            'date_range' => 'nullable|in:1day,3days,1week,1month',
            'per_page' => 'nullable|numeric',
            'page' => 'nullable|numeric'
        ];
    }
}
