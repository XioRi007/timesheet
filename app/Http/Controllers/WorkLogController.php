<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Requests\UpdateWorkLogRequest;
use App\Models\WorkLog;

class WorkLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workLogs = WorkLog::all();
        return $workLogs;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkLogRequest $request)
    {
        $workLog = WorkLog::create($request->validated());
        return $workLog->id;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkLog $workLog)
    {
        return $workLog;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkLog $workLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkLogRequest $request, WorkLog $workLog)
    {
        $workLog->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkLog $workLog)
    {
        $workLog->delete();
    }
}
