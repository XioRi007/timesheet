<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Requests\UpdateWorkLogRequest;
use App\Models\Developer;
use App\Models\Project;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WorkLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $workLogs = WorkLog::with('developer:id,first_name,last_name')
            ->with('project:id,name')
            ->get(['created_at', 'project_id', 'developer_id', 'rate', 'status', 'hrs', 'total', 'id']);
        $transformedWorkLogs = $workLogs->map(function ($log) {
            return [
                'date' => Carbon::parse($log->created_at)->toDateString(),
                'developer' => $log->developer->full_name,
                'project' => $log->project->name,
                'rate' => $log->rate,
                'hrs' => $log->hrs,
                'total' => $log->total,
                'status' => $log->status,
                'id' => $log->id,
            ];
        });
        return Inertia::render('WorkLog/Index', [
            'worklogs' => $transformedWorkLogs,
            'message' =>$this->getMessage($request)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkLogRequest $request)
    {
        WorkLog::CheckMaxHoursToday($request->validated('developer_id'), $request->validated('hrs'));
        WorkLog::create($request->validated());
        return to_route('worklogs.index', ['message'=>'Wor Log was successfully created']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $res = $this->getDeveloperProjectRate($request);
        $developer = $res['developer'];
        $project = $res['project'];
        $rate = $res['rate'];
        $developers = Developer::select(DB::raw('id, CONCAT(first_name, " ", last_name) AS name'))->get();
        $projects = Project::all('id', 'name');
        return Inertia::render('WorkLog/Create', [
            'developers' => $developers,
            'projects' => $projects,
            'rate' => $rate,
            'developer' => $developer,
            'project' => $project,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkLog $worklog)
    {
        return $worklog;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, WorkLog $worklog)
    {
        $res = $this->getDeveloperProjectRate($request);
        $developer = $res['developer'];
        $project = $res['project'];
        $rate = $res['rate'];
        $developers = Developer::select(DB::raw('id, CONCAT(first_name, " ", last_name) AS name'))->get();
        $projects = Project::all('id', 'name');
        return Inertia::render('WorkLog/Edit', [
            'developers' => $developers,
            'projects' => $projects,
            'worklog' => $worklog,
            'rate' => $rate,
            'developer' => $developer,
            'project' => $project,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkLogRequest $request, WorkLog $worklog)
    {
        WorkLog::CheckMaxHoursToday($request->validated('developer_id'), $request->validated('hrs'), $worklog->id);
        $worklog->update($request->validated());
        return to_route('worklogs.index', ['message'=>'Wor Log was successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkLog $worklog)
    {
        $worklog->delete();
    }

    private function getDeveloperProjectRate(Request $request): array
    {
        $developer = null;
        $project = null;
        if ($request->query->has('developer')) {
            $developer = intval($request->query->get('developer'));
        }
        if ($request->query->has('project')) {
            $project = intval($request->query->get('project'));
        }
        $rate = WorkLog::GetRate($developer, $project);
        return [
            'developer' => $developer,
            'project' => $project,
            'rate' => $rate,
        ];
    }
}
