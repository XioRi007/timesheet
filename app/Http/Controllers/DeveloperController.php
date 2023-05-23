<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeveloperRequest;
use App\Http\Requests\UpdateDeveloperRequest;
use App\Models\Developer;
use Inertia\Inertia;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $developers = Developer::all('first_name', 'last_name', 'rate', 'rate_percent', 'status', 'id');
        return Inertia::render('Developer/Index', [
            'developers' => $developers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeveloperRequest $request)
    {
        Developer::create($request->validated());
        return to_route('developers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Developer/Create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Developer $developer)
    {
        return $developer;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Developer $developer)
    {
        return Inertia::render('Developer/Edit', [
            'developer' => $developer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeveloperRequest $request, Developer $developer)
    {
        $developer->update($request->validated());
        return to_route('developers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Developer $developer)
    {
        try {
            $developer->delete();
        } catch (\Throwable $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception('You cannot delete developer with work logs');
            } else {
                throw $e;
            }
        }
    }
}
