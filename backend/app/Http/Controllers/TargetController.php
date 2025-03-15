<?php

namespace App\Http\Controllers;

use App\Http\Requests\TargetRequest;
use App\Http\Resources\TargetResource;
use App\Models\Target;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Get all targets success.',
            'data' => TargetResource::collection(Target::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TargetRequest $request)
    {
        $target = auth()->user()->targets()->create($request->validated());

        return response()->json([
            'message' => 'Create target success.',
            'data' => TargetResource::make($target)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Target $target)
    {
        return response()->json([
            'message' => 'Get target success.',
            'data' => TargetResource::make($target)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TargetRequest $request, Target $target)
    {
        $target->update($request->validated());

        return response()->json([
            'message' => 'Update target success.',
            'data' => TargetResource::make($target->refresh())
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Target $target)
    {
        $target->delete();

        return response()->json([
            'message' => 'delete target success.',
            'data' => TargetResource::make($target)
        ], 200);
    }
}
