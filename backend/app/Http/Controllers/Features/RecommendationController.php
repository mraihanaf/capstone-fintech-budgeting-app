<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\RecommendationRequest;
use App\Models\Recommendation;
use App\Http\Resources\RecommendationResource;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;

class RecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Get all recommendations success.',
            'data' => RecommendationResource::collection(Recommendation::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecommendationRequest $request)
    {
        function convertToUsd($amount)
        {
            return CurrencyConverter::convert($amount)->from('IDR')->to('USD')->get();
        }

        $annualIncome = Transaction::where('transaction_date', '>=', Carbon::now()->subYear()->format('Y-m-d'))->where('type', 'income')->sum('amount');

        $monthlyInhandSalary = Transaction::where('transaction_date', '>=', Carbon::now()->subMonth()->format('Y-m-d'))->where('type', 'income')->sum('amount');

        $numBankAccount = 0;

        $numCreditCard = 0;

        $interestRate = 0.0;

        $numOfLoan = 0;

        $delayFromDueDate = 0;

        $numOfDelayedPayment = 0;

        $changedCreditLimit = 0.0;

        $numCreditInquiries = 0;

        $outstandingDebt = 0.0;

        $creditUtilizationRatio = 0.0;

        $totalEmiPerMonth = 0.0;

        $amountInvestedMonthly = 0.0;

        $monthlyBalance = 0.0;

        $creditMix = '';

        $paymentOfMinAmount = '';

        dd(convertToUsd($annualIncome));

        $recommendation = Http::post('', [
            # data angka
            'Annual_Income' => convertToUsd($annualIncome),
            'Monthly_Inhand_Salary' => convertToUsd($monthlyInhandSalary),
            'Num_Bank_Accounts' => $numBankAccount,
            'Num_Credit_Card' => $numCreditCard,
            'Interest_Rate' => convertToUsd($interestRate),
            'Num_of_Loan' => $numOfLoan,
            'Delay_from_due_date' => $delayFromDueDate,
            'Num_of_Delayed_Payment' => $numOfDelayedPayment,
            'Changed_Credit_Limit' => convertToUsd($changedCreditLimit),
            'Num_Credit_Inquiries' => $numCreditInquiries,
            'Outstanding_Debt' => convertToUsd($outstandingDebt),
            'Credit_Utilization_Ratio' => convertToUsd($creditUtilizationRatio),
            'Total_EMI_per_month' => convertToUsd($totalEmiPerMonth),
            'Amount_invested_monthly' => convertToUsd($amountInvestedMonthly),
            'Monthly_Balance' => convertToUsd($monthlyBalance),
            # data kategori
            'Credit_Mix' => $creditMix,
            'Payment_of_Min_Amount' => $paymentOfMinAmount,
        ]);

        return response()->json([
            'message' => 'Create recommendation success.',
            'data' => new RecommendationResource($recommendation)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recommendation $recommendation)
    {
        return response()->json([
            'message' => 'Get recommendation success.',
            'data' => new RecommendationResource($recommendation)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RecommendationRequest $request, Recommendation $recommendation)
    {
        $recommendation->update($request->validated());

        return response()->json([
            'message' => 'Update recommendation success.',
            'data' => new RecommendationResource($recommendation->refresh())
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recommendation $recommendation)
    {
        $recommendation->delete();

        return response()->json([
            'message' => 'delete recommendation success.',
            'data' => new RecommendationResource($recommendation)
        ], 200);
    }
}
