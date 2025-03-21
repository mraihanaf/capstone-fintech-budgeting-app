<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\TransactionRequest;
use App\Http\Requests\Filters\TransactionFilterRequest;
use App\Http\Resources\LogResource;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TransactionFilterRequest $request)
    {
        $validated = $request->validated();

        $transactions = Transaction::query()
            ->where('user_id', auth('api')->id())
            ->when($validated['is_recurring'] ?? null, fn($q) => $q->where('is_recurring', $validated['is_recurring']))
            ->when($validated['category_id'] ?? null, fn($q) => $q->where('category_id', $validated['category_id']))
            ->when($validated['type'] ?? null, fn($q) => $q->where('type', $validated['type']))
            ->when($validated['min_amount'] ?? null, fn($q) => $q->where('amount', '>=', $validated['min_amount']))
            ->when($validated['max_amount'] ?? null, fn($q) => $q->where('amount', '<=', $validated['max_amount']))
            ->when($validated['date_range'] ?? null, function ($q) use ($validated) {
                $date = match ($validated['date_range']) {
                    '1day' => Carbon::now()->subDay()->format('Y-m-d'),
                    '3days' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    '1week' => Carbon::now()->subWeek()->format('Y-m-d'),
                    '1month' => Carbon::now()->subMonth()->format('Y-m-d'),
                };

                return $q->where('transaction_date', '>=', $date);
            })
            ->when($validated['sort_by'] ?? null, fn($q) => $q->orderBy($validated['sort_by'] ?? 'transaction_date', $validated['sort_order'] ?? 'asc'))
            ->when($validated['sort_order'] ?? null, fn($q) => $q->orderBy($validated['sort_by'] ?? 'transaction_date', $validated['sort_order'] ?? 'asc'))
            ->paginate($validated['per_page'] ?? 10);

        return response()->json([
            'message' => 'Get all transactions success.',
            'data' => TransactionResource::collection($transactions),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'next_page_url' => $transactions->nextPageUrl(),
                'prev_page_url' => $transactions->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        $category = Category::where('id', $validated['category_id'])->first();

        $transaction = auth('api')->user()->transactions()->create($validated);

        $log = auth('api')->user()->logs()->create([
            'action' => "Add {$validated['type']}",
            'details' => "{$category['name']} - Rp{$validated['amount']}"
        ]);

        return response()->json([
            'message' => 'Create transaction success.',
            'data' => new TransactionResource($transaction),
            'log' => new LogResource($log)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()->json([
            'message' => 'Get transaction success.',
            'data' => new TransactionResource($transaction)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();
        $oldAmount = $transaction->amount;
        $category = Category::where('id', $validated['category_id'])->first();

        $transaction->update($validated);

        $log = auth('api')->user()->logs()->create([
            'action' => "Update {$validated['type']}",
            'details' => "{$category['name']} - {$oldAmount} -> Rp{$validated['amount']}",
        ]);

        return response()->json([
            'message' => 'Update transaction success.',
            'data' => new TransactionResource($transaction->refresh()),
            'log' => new LogResource($log)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $category = Category::where('id', $transaction->category_id)->first();

        $transaction->delete();

        $log = auth('api')->user()->logs()->create([
            'action' => "Delete {$transaction->type}",
            'details' => "{$category['name']} - Rp{$transaction->amount}"
        ]);

        return response()->json([
            'message' => 'delete transaction success.',
            'data' => new TransactionResource($transaction),
            'log' => new LogResource($log)
        ], 200);
    }
}
