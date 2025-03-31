<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\LogRequest;
use App\Http\Requests\Filters\LogFilterRequest;
use App\Models\Log;
use App\Http\Resources\LogResource;
use Illuminate\Support\Facades\Cache;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LogFilterRequest $request)
    {
        $validated = $request->validated();
        $userId = auth('api')->id();
        $encodedValidated = md5(json_encode($validated));

        $log = Cache::remember(
            "logs_{$userId}_{$encodedValidated}",
            now()->addMinutes(15),
            fn() =>
            Log::where('user_id', $userId)
                ->filters($validated)
                ->paginate($validated['per_page'] ?? 10)
        );

        return response()->json([
            'message' => 'Get all logs success.',
            'data' => LogResource::collection(Log::all()),
            'pagination' => [
                'total' => $log->total(),
                'per_page' => $log->perPage(),
                'current_page' => $log->currentPage(),
                'last_page' => $log->lastPage(),
                'next_page_url' => $log->nextPageUrl(),
                'prev_page_url' => $log->previousPageUrl(),
                'path' => $log->path(),
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LogRequest $request)
    {
        $log = auth('api')->user()->logs()->create($request->validated());
        Cache::tags(['logs'])->flush();

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
        Cache::tags(['logs'])->flush();

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
        Cache::tags(['logs'])->flush();

        return response()->json([
            'message' => 'delete log success.',
            'data' => new LogResource($log)
        ], 200);
    }
}
