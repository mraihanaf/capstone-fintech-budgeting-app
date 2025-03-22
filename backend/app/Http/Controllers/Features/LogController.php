<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\LogRequest;
use App\Http\Requests\Filters\LogFilterRequest;
use App\Models\Log;
use App\Http\Resources\LogResource;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LogFilterRequest $request)
    {
        $validated = $request->validated();

        $log = Log::query()
            ->where('user_id', auth('api')->id())
            ->when($validated['sort_by'] ?? null, fn($q) => $q->orderBy($validated['sort_by'] ?? 'created_at', $validated['sort_order'] ?? 'asc'))
            ->when($validated['sort_order'] ?? null, fn($q) => $q->orderBy($validated['sort_by'] ?? 'created_at', $validated['sort_order'] ?? 'asc'))
            ->paginate($validated['per_page'] ?? 10);

        return response()->json([
            'message' => 'Get all logs success.',
            'data' => LogResource::collection(Log::all()),
            'pagination' => [
                'current_page' => $log->currentPage(),
                'last_page' => $log->lastPage(),
                'per_page' => $log->perPage(),
                'total' => $log->total(),
                'next_page_url' => $log->nextPageUrl(),
                'prev_page_url' => $log->previousPageUrl()
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LogRequest $request)
    {
        $log = auth('api')->user()->logs()->create($request->validated());

        return response()->json([
            'message' => 'Create log success.',
            'data' => new LogResource($log)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        return response()->json([
            'message' => 'Get log success.',
            'data' => new LogResource($log)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LogRequest $request, Log $log)
    {
        $log->update($request->validated());

        return response()->json([
            'message' => 'Update log success.',
            'data' => new LogResource($log->refresh())
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log $log)
    {
        $log->delete();

        return response()->json([
            'message' => 'delete log success.',
            'data' => new LogResource($log)
        ], 200);
    }
}