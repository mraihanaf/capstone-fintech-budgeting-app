<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Http\Requests\BudgetRequest;
use App\Http\Resources\BudgetResource;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Get all budgets success.',
            'data' => BudgetResource::collection(Budget::with('category')->get())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BudgetRequest $request)
    {
        // Kalau merah biarin aja, masih tetep jalan. Extension Intelephense ga bisa ngedeteksi method user()
        $budget = auth()->user()->budgets()->create($request->validated());

        return response()->json([
            'message' => 'Create budget success.',
            'data' => BudgetResource::make($budget)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        return response()->json([
            'message' => 'Get budget success.',
            'data' => BudgetResource::make($budget)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BudgetRequest $request, Budget $budget)
    {
        $budget->update($request->validated());

        return response()->json([
            'message' => 'Update budget success.',
            'data' => BudgetResource::make($budget)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();

        return response()->json([
            'message' => 'delete budget success.',
            'data' => BudgetResource::make($budget)
        ], 200);
    }
}