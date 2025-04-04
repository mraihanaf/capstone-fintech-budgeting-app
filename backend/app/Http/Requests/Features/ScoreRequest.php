<?php

namespace App\Http\Requests\Features;

use Illuminate\Foundation\Http\FormRequest;

class ScoreRequest extends FormRequest
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
            "Num_Bank_Accounts" => 'integer',
            "Num_Credit_Card" => 'integer',
            "Interest_Rate" => 'decimal:0,999999999999999',
            "Num_of_Loan" => 'integer',
            "Delay_from_due_date" => 'integer',
            "Num_of_Delayed_Payment" => 'integer',
            "Changed_Credit_Limit" => 'decimal:0,999999999999999',
            "Num_Credit_Inquiries" => 'integer',
            "Outstanding_Debt" => 'decimal:0,999999999999999',
            "Credit_Utilization_Ratio" => 'decimal:0,999999999999999',
            "Total_EMI_per_month" => 'decimal:0,999999999999999',
            "Amount_invested_monthly" => 'decimal:0,999999999999999',
            "Credit_Mix" => 'required|in:Good,Bad',
            "Payment_of_Min_Amount" => 'required|in:Yes,No'
        ];
    }
}
