<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $clients = Client::query()
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%'.request('search').'%')
                    ->orWhere('email', 'like', '%'.request('search').'%')
                    ->orWhere('contact', 'like', '%'.request('search').'%');
            })
            ->orderBy('created_at', 'desc') // Newest first
            ->paginate(10);
    
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'contact' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);
    
        $client = Client::create($validated);
    
        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,'.$client->id,
            'contact' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès');
    }
}