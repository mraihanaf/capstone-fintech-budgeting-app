<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\RecommendationRequest;
use App\Models\Recommendation;
use App\Http\Resources\RecommendationResource;

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
        $recommendation = auth('api')->user()->recommendations()->create($request->validated());

        return response()->json([
            'message' => 'Create recommendation success.',
            'data' => RecommendationResource::make($recommendation)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recommendation $recommendation)
    {
        return response()->json([
            'message' => 'Get recommendation success.',
            'data' => RecommendationResource::make($recommendation)
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
            'data' => RecommendationResource::make($recommendation->refresh())
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
            'data' => RecommendationResource::make($recommendation)
        ], 200);
    }
}
