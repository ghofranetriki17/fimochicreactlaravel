<?php

namespace App\Http\Controllers;

use App\Models\RessourcePersonnalisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RessourcePersonnalisationController extends Controller
{
    /**
     * Affiche la liste des ressources personnalisées en format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $ressourcesParCatEtType = RessourcePersonnalisation::all()->groupBy(['cat', 'type']);
        return response()->json([
            'status' => 'success',
            'data' => $ressourcesParCatEtType
        ], 200);
    }

    /**
     * Affiche le formulaire pour créer une nouvelle ressource personnalisée.
     * (Note : Utilisé ici pour informer l'utilisateur des données attendues)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Envoyez les données suivantes pour créer une ressource personnalisée.'
        ], 200);
    }

    /**
     * Stocke une nouvelle ressource personnalisée dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'cat' => 'required|string|max:255',
            'prix' => 'required|numeric'
        ]);

        $photoName = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('img'), $photoName);
        }

        $ressource = new RessourcePersonnalisation();
        $ressource->nom = $request->nom;
        $ressource->prix = $request->prix;
        $ressource->type = $request->type;
        $ressource->cat = $request->cat;
        $ressource->image = $photoName;
        $ressource->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Ressource ajoutée avec succès.',
            'data' => $ressource
        ], 201);
    }

    /**
     * Affiche les informations d'une ressource personnalisée spécifique en format JSON.
     *
     * @param  \App\Models\RessourcePersonnalisation  $ressource_personnalisation
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(RessourcePersonnalisation $ressource_personnalisation)
    {
        return response()->json([
            'status' => 'success',
            'data' => $ressource_personnalisation
        ], 200);
    }

    /**
     * Met à jour une ressource personnalisée existante dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RessourcePersonnalisation  $ressource_personnalisation
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, RessourcePersonnalisation $ressource_personnalisation)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'cat' => 'required|string|max:255',
            'prix' => 'required|numeric'
        ]);

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne image si elle existe
            if ($ressource_personnalisation->image) {
                Storage::disk('public')->delete('img/' . $ressource_personnalisation->image);
            }

            // Enregistrer la nouvelle image
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('img'), $photoName);
            $ressource_personnalisation->image = $photoName;
        }

        $ressource_personnalisation->nom = $request->nom;
        $ressource_personnalisation->prix = $request->prix;
        $ressource_personnalisation->type = $request->type;
        $ressource_personnalisation->cat = $request->cat;
        $ressource_personnalisation->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Ressource mise à jour avec succès.',
            'data' => $ressource_personnalisation
        ], 200);
    }

    /**
     * Supprime une ressource personnalisée de la base de données.
     *
     * @param  \App\Models\RessourcePersonnalisation  $ressource_personnalisation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RessourcePersonnalisation $ressource_personnalisation)
    {
        // Supprimer l'image associée à la ressource
        if ($ressource_personnalisation->image) {
            Storage::disk('public')->delete('img/' . $ressource_personnalisation->image);
        }

        $ressource_personnalisation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ressource supprimée avec succès.'
        ], 200);
    }
}
