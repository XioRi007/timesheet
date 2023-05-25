<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
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
        $clients = Client::filter($filterParams)
            ->sort($column, $ascending)
            ->get(['id', 'name', 'rate', 'status']);
        return Inertia::render('Client/Index', [
            'clients' => $clients,
            'filterParams' => $filterParams,
            'column' => $column,
            'ascending' => $ascending == 'asc',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        Client::create($request->validated());
        return to_route('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Client/Create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): Client
    {
        return $client;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client): Response
    {
        return Inertia::render('Client/Edit', [
            'client' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());
        return to_route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): void
    {
        try {
            $client->delete();
        } catch (\Throwable $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception('You cannot delete client with projects');
            } else {
                throw $e;
            }
        }
    }
}
