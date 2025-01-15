<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use Illuminate\Http\Request;

class AttributController extends Controller
{
    /**
     * Affiche une liste des ressources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Récupérer tous les attributs
        $attributs = Attribut::all();

        // Retourner les attributs en JSON
        return response()->json(['attributs' => $attributs]);
    }

    /**
     * Stocke une nouvelle ressource nouvellement créée dans le stockage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        // Créer un nouvel attribut
        $attribut = Attribut::create($validatedData);

        // Retourner une réponse JSON
        return response()->json(['message' => 'Attribut créé avec succès.', 'attribut' => $attribut]);
    }

    /**
     * Affiche la ressource spécifiée.
     *
     * @param  Attribut  $attribut
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Attribut $attribut)
    {
        return response()->json(['attribut' => $attribut]);
    }

    /**
     * Met à jour la ressource spécifiée dans le stockage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Attribut  $attribut
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Attribut $attribut)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        // Mettre à jour l'attribut
        $attribut->update($validatedData);

        // Retourner une réponse JSON
        return response()->json(['message' => 'Attribut mis à jour avec succès.', 'attribut' => $attribut]);
    }

    /**
     * Supprime la ressource spécifiée du stockage.
     *
     * @param  Attribut  $attribut
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Attribut $attribut)
    {
        // Supprimer l'attribut
        $attribut->delete();

        // Retourner une réponse JSON
        return response()->json(['message' => 'Attribut supprimé avec succès.']);
    }
}
