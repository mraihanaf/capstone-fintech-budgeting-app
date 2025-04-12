<?php

namespace App\Http\Controllers\Features;

use App\Classes\QueryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Features\TransactionRequest;
use App\Http\Requests\Filters\TransactionFilterRequest;
use App\Http\Resources\LogResource;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TransactionFilterRequest $request)
    {
        $validated = $request->validated();
        $userId = auth('api')->id();
        $encodedValidated = md5(json_encode($validated));

        $transactions = Cache::remember(
            "transactions_{$userId}_{$encodedValidated}",
            now()->addMinutes(15),
            fn() =>
            Transaction::where('user_id', $userId)
                ->filters($validated)
                ->paginate($validated['per_page'] ?? 10)
        );

        return response()->json([
            'message' => 'Get all transactions success.',
            'data' => TransactionResource::collection($transactions),
            'pagination' => [
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'next_page_url' => $transactions->nextPageUrl(),
                'prev_page_url' => $transactions->previousPageUrl(),
                'path' => $transactions->path(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        $user = auth('api')->user();

        $category = Category::findOrFail($validated['category_id']);

        $transaction = auth('api')->user()->transactions()->create($validated);
        Cache::tags(['transactions'])->flush();

        $newBalance = $transaction['type'] === 'income' ?
            $user->balance + $transaction['amount'] :
            $user->balance - $transaction['amount'];
        $user->update(['balance' => $newBalance]);

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

        $category = Category::findOrFail($validated['category_id']);

        $transaction->update($validated);
        Cache::tags(['transactions'])->flush();

        $user = auth('api')->user();
        $newBalance = $transaction['type'] === 'income' ?
            $user->balance + $transaction['amount'] :
            $user->balance - $transaction['amount'];
        $user->update(['balance' => $newBalance]);

        $log = auth('api')->user()->logs()->create([
            'action' => "Update {$validated['type']}",
            'details' => "{$category['name']} - Rp{$oldAmount} -> Rp{$validated['amount']}",
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
        $category = Category::findOrFail($transaction->category_id);

        $transaction->delete();
        Cache::tags(['transactions'])->flush();

        $user = auth('api')->user();
        $newBalance = $transaction['type'] === 'income' ?
            $user->balance - $transaction['amount'] :
            $user->balance + $transaction['amount'];
        $user->update(['balance' => $newBalance]);

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
