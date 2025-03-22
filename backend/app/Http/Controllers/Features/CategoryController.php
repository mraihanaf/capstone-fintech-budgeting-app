<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\CategoryRequest;
use App\Http\Requests\Filters\CategoryFilterRequest;
use App\Models\Category;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\LogResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryFilterRequest $request)
    {
        $validated = $request->validated();

        $category = Category::query()
            ->where('user_id', auth('api')->id())
            ->when($validated['type'] ?? null, fn($q) => $q->where('type', $validated['type']))
            ->when($validated['sort_by'] ?? null, fn($q) => $q->orderBy($validated['sort_by'] ?? 'created_at', $validated['sort_order'] ?? 'asc'))
            ->when($validated['sort_order'] ?? null, fn($q) => $q->orderBy($validated['sort_by'] ?? 'created_at', $validated['sort_order'] ?? 'asc'))
            ->paginate($validated['per_page'] ?? 10);

        return response()->json([
            'message' => 'Get all categories success.',
            'data' => CategoryResource::collection($category),
            'pagination' => [
                'current_page' => $category->currentPage(),
                'last_page' => $category->lastPage(),
                'per_page' => $category->perPage(),
                'total' => $category->total(),
                'next_page_url' => $category->nextPageUrl(),
                'prev_page_url' => $category->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = auth('api')->user()->categories()->create($request->validated());

        $log = auth('api')->user()->logs()->create([
            'action' => "Add category",
            'details' => $category['name']
        ]);

        return response()->json([
            'message' => 'Create category success.',
            'data' => new CategoryResource($category),
            'log' => new LogResource($log)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'message' => 'Get category success.',
            'data' => new CategoryResource($category)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $oldName = $category->name;
        $category->update($request->validated());

        $log = auth('api')->user()->logs()->create([
            'action' => "Update category",
            'details' => $oldName . '->' . $category['name']
        ]);

        return response()->json([
            'message' => 'Update category success.',
            'data' => new CategoryResource($category->refresh()),
            'log' => new LogResource($log)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        $log = auth('api')->user()->logs()->create([
            'action' => "Delete category",
            'details' => $category['name']
        ]);

        return response()->json([
            'message' => 'delete category success.',
            'data' => new CategoryResource($category),
            'log' => new LogResource($log)
        ], 200);
    }
}
