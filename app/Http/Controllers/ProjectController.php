<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->ParseQuery($request);
        $filterParams = $query['filterParams'];
        $column = $query['column'];
        $ascending = $query['ascending'];
        $clients = Client::all('id', 'name');
        $projects = Project::with('client:id,name')
            ->filter($filterParams)
            ->sort($column, $ascending)
            ->get(['name', 'client_id', 'rate', 'status', 'id']);

        $transformedProjects = $projects->map(function ($project) {
            return [
                'name' => $project->name,
                'client.name' => $project->client->name,
                'rate' => $project->rate,
                'status' => $project->status,
                'id' => $project->id,
            ];
        });
        return Inertia::render('Project/Index', [
            'projects' => $transformedProjects,
            'filterParams' => $filterParams,
            'column' => $column,
            'ascending' => $ascending == 'asc',
            'clients' => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Project::create($request->validated());
        return to_route('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all('id', 'name');
        return Inertia::render('Project/Create', [
            'clients' => $clients
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::all('id', 'name');
        return Inertia::render('Project/Edit', [
            'project' => $project,
            'clients' => $clients
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return to_route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
        } catch (\Throwable $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception('You cannot delete project with work logs');
            } else {
                throw $e;
            }
        }
    }
}
