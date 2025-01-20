<?php

namespace App\Http\Controllers;

use App\Models\ClientRessourcePersonnalisation;
use Illuminate\Http\Request;

class ClientRessourcePersonnalisationController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        // Fetch all resources for client_id 2
        $clientRessources = ClientRessourcePersonnalisation::where('client_id', 2)->get();
        return response()->json($clientRessources);
    }

    // Show the form for creating a new resource
    public function create()
    {
        // This can be expanded to return a view for creating the resource
        return response()->json(['message' => 'Create resource for client_id 2']);
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        // Ensure client_id is always 2
        $validatedData = $request->validate([
            'ressource_personnalisation_id' => 'required|integer',
            'quantite' => 'required|integer',
            'prix_total' => 'required|numeric',
        ]);

        $validatedData['client_id'] = 2;

        $clientRessource = ClientRessourcePersonnalisation::create($validatedData);

        return response()->json($clientRessource, 201);
    }

    // Display the specified resource
    public function show($id)
    {
        // Fetch resource by ID for client_id 2
        $clientRessource = ClientRessourcePersonnalisation::where('client_id', 2)->findOrFail($id);
        return response()->json($clientRessource);
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        // Similar to create, but for editing existing records
        return response()->json(['message' => 'Edit resource for client_id 2']);
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        // Ensure client_id is always 2
        $validatedData = $request->validate([
            'ressource_personnalisation_id' => 'required|integer',
            'quantite' => 'required|integer',
            'prix_total' => 'required|numeric',
        ]);

        $validatedData['client_id'] = 2;

        $clientRessource = ClientRessourcePersonnalisation::where('client_id', 2)->findOrFail($id);
        $clientRessource->update($validatedData);

        return response()->json($clientRessource);
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        // Delete the resource for client_id 2
        $clientRessource = ClientRessourcePersonnalisation::where('client_id', 2)->findOrFail($id);
        $clientRessource->delete();

        return response()->json(['message' => 'Resource deleted']);
    }
}
