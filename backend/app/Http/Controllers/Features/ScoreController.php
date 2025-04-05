<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\ScoreRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;

class ScoreController extends Controller
{
    public function check(ScoreRequest $request)
    {
        $validated = $request->validated();

        function convertToUsd($amount)
        {
            return CurrencyConverter::convert($amount)->from('IDR')->to('USD')->get();
        }

        $annual = Transaction::where('transaction_date', '>=', Carbon::now()->subYear()->format('Y-m-d'))->where('type', 'income')->sum('amount');
        $monthly = Transaction::where('transaction_date', '>=', Carbon::now()->subMonth()->format('Y-m-d'))->where('type', 'income')->sum('amount');

        $requestBody = [
            # data angka
            'Annual_Income' => $annual < 0 ? convertToUsd($annual) : 0,
            'Monthly_Inhand_Salary' => $monthly < 0 ? convertToUsd($monthly) : 0,
            'Num_Bank_Accounts' => $validated['Num_Bank_Accounts'],
            'Num_Credit_Card' => $validated['Num_Credit_Card'],
            'Interest_Rate' => $validated['Interest_Rate'] < 0 ? convertToUsd($validated['Interest_Rate']) : 0,
            'Num_of_Loan' => $validated['Num_of_Loan'],
            'Delay_from_due_date' => $validated['Delay_from_due_date'],
            'Num_of_Delayed_Payment' => $validated['Num_of_Delayed_Payment'],
            'Changed_Credit_Limit' => $validated['Changed_Credit_Limit'] < 0 ? convertToUsd($validated['Changed_Credit_Limit']) : 0,
            'Num_Credit_Inquiries' => $validated['Num_Credit_Inquiries'],
            'Outstanding_Debt' => $validated['Outstanding_Debt'] < 0 ? convertToUsd($validated['Outstanding_Debt']) : 0,
            'Credit_Utilization_Ratio' => $validated['Credit_Utilization_Ratio'] < 0 ? convertToUsd($validated['Credit_Utilization_Ratio']) : 0,
            'Total_EMI_per_month' => $validated['Total_EMI_per_month'] < 0 ? convertToUsd($validated['Total_EMI_per_month']) : 0,
            'Amount_invested_monthly' => $validated['Amount_invested_monthly'] < 0 ? convertToUsd($validated['Amount_invested_monthly']) : 0,
            'Monthly_Balance' => auth('api')->user()->balance < 0 ? convertToUsd(auth('api')->user()->balance) : 0,
            # data kategori
            'Credit_Mix' => $validated['Credit_Mix'],
            'Payment_of_Min_Amount' => $validated['Payment_of_Min_Amount'],
        ];

        $score = Http::post(env('ML_ENDPOINT'), $requestBody);

        $log = auth('api')->user()->logs()->create([
            'action' => "Check Score",
            'details' => 'Score: ' . $score->json()['credit_score']
        ]);

        return response()->json([
            'message' => 'Check score success.',
            'data' => $score->json(),
            'log' => $log
        ], 201);
    }
}
