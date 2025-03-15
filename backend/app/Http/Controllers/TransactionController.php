<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Get all transactions success.',
            'data' => TransactionResource::collection(Transaction::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $transaction = auth()->user()->transactions()->create($request->validated());

        return response()->json([
            'message' => 'Create transaction success.',
            'data' => TransactionResource::make($transaction)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()->json([
            'message' => 'Get transaction success.',
            'data' => TransactionResource::make($transaction)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());

        return response()->json([
            'message' => 'Update transaction success.',
            'data' => TransactionResource::make($transaction->refresh())
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json([
            'message' => 'delete transaction success.',
            'data' => TransactionResource::make($transaction)
        ], 200);
    }
}