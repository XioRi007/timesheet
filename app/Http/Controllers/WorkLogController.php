<?php

namespace App\Http\Controllers;

use App\Events\WorkLogCreatedByDeveloper;
use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Requests\UpdateWorkLogRequest;
use App\Models\Developer;
use App\Models\Project;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class WorkLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = $this->ParseQuery($request);
        $filterParams = $query['filterParams'];
        $column = $query['column'];
        $ascending = $query['ascending'];
        $developers = Developer::select(DB::raw('id, CONCAT(first_name, " ", last_name) AS name'))->get();
        $projects = Project::all('id', 'name');
        $workLogs = WorkLog::with('developer:id,first_name,last_name')
            ->with('project:id,name')
            ->filter($filterParams)
            ->sort($column, $ascending)
            ->paginate(50, ['created_at', 'developer_id as developer.full_name', 'developer_id', 'project_id as project.name', 'project_id', 'rate', 'hrs', 'total', 'status', 'id'])
            ->withQueryString()
            ->through(function ($log, $key) {
                $log['developer.full_name'] = $log->developer->full_name;
                $log['project.name'] = $log->project->name;
                $log['created_at'] = Carbon::parse($log->created_at)->toDateString();
                unset($log['developer_id']);
                unset($log['project_id']);
                unset($log['project']);
                unset($log['developer']);
                return $log;
            });
        return Inertia::render('WorkLog/Index', [
            'worklogs' => $workLogs,
            'developers' => $developers,
            'projects' => $projects,
            'filterParams' => $filterParams,
            'column' => $column,
            'ascending' => $ascending == 'asc',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkLogRequest $request)
    {
        WorkLog::CheckMaxHoursToday($request->validated('developer_id'), $request->validated('hrs'));
        WorkLog::create($request->validated());
        if($request->user()->hasRole('developer')){
            WorkLogCreatedByDeveloper::dispatch($request->validated('developer_id'), $request->validated('project_id'));
            return to_route('developers.worklogs', $request->user()->developer->id);
        }
        return to_route('worklogs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $res = $this->getDeveloperProjectRate($request);
        $user = $request->user();
        if($user->hasRole('developer')){
            $developer = $user->developer->id;
            $developers = [];
        }else{
            $developer = $res['developer'];
            $developers = Developer::select(DB::raw('id, CONCAT(first_name, " ", last_name) AS name'))->get();
        }
        $res = $this->getDeveloperProjectRate($request);
        $project = $res['project'];
        $rate = $res['rate'];
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
        WorkLog::CheckMaxHoursToday($request->validated('developer_id'), $request->validated('hrs'), $worklog->id, $worklog->created_at);
        $worklog->update($request->validated());
        return to_route('worklogs.index');
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
