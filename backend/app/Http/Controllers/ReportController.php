<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Get all reports success.',
            'data' => ReportResource::collection(Report::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportRequest $request)
    {
        $report = Report::create($request->validated());

        return response()->json([
            'message' => 'Create report success.',
            'report' => ReportResource::make($report)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        return response()->json([
            'message' => 'Get report success.',
            'report' => ReportResource::make($report)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReportRequest $request, Report $report)
    {
        $updatedReport = $report->update($request->validated());

        return response()->json([
            'message' => 'Update report success.',
            'report' => ReportResource::make($updatedReport)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $report->delete();
        return response()->json([
            'message' => 'delete report success.'
        ], 200);
    }
}
