<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    /**
     * Show the form for creating a new client.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json(['message' => 'Form for creating a new client']);
    }

    /**
     * Store a newly created client in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'prenom' => 'nullable|string|max:255',
            'nom' => 'nullable|string|max:255',
            'mail' => 'nullable|email|max:255',
            'age' => 'nullable|integer|min:0',
            'numero_tel' => 'nullable|numeric',
            'sexe' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->nom,
            'email' => $request->mail,
            'password' => Hash::make($request->password),
        ]);

        // Create the client associated with the user
        $client = Client::create([
            'user_id' => $user->id,
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'mail' => $request->mail,
            'age' => $request->age,
            'numero_tel' => $request->numero_tel,
            'gender' => $request->sexe,
            'adresse' => $request->adresse,
        ]);

        return response()->json([
            'message' => 'Client added successfully.',
            'client' => $client
        ], 201);  // HTTP status code 201 for created resource
    }

    /**
     * Display the specified client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return response()->json(['message' => 'Form for editing client', 'client' => $client]);
    }

    /**
     * Update the specified client in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'prenom' => 'nullable|string|max:255',
            'nom' => 'nullable|string|max:255',
            'mail' => 'nullable|email|max:255',
            'age' => 'nullable|integer|min:0',
            'numero_tel' => 'nullable|numeric',
            'sexe' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        $client = Client::findOrFail($id);

        $client->update([
            'prenom' => $request->input('prenom', $client->prenom),
            'nom' => $request->input('nom', $client->nom),
            'mail' => $request->input('mail', $client->mail),
            'age' => $request->input('age', $client->age),
            'numero_tel' => $request->input('numero_tel', $client->numero_tel),
            'sexe' => $request->input('sexe', $client->sexe),
            'adresse' => $request->input('adresse', $client->adresse),
        ]);

        return response()->json([
            'message' => 'Client updated successfully.',
            'client' => $client
        ]);
    }

    /**
     * Remove the specified client from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully.'
        ]);
    }

    /**
     * Show the client's account details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function compte()
    {
        $client = Auth::user()->client;

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        // Retrieve the client's orders
        $commandes = $client->commandes;

        return response()->json([
            'client' => $client,
            'commandes' => $commandes
        ]);
    }
}
