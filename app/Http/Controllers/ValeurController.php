<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use App\Models\Valeur;
use Illuminate\Http\Request;

class ValeurController extends Controller
{
    /**
     * Affiche une liste des valeurs pour un attribut donné.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Récupérer tous les attributs avec leurs valeurs associées
        $attributs = Attribut::with('valeurs')->get();

        // Retourner les attributs et leurs valeurs en JSON
        return response()->json(['attributs' => $attributs]);
    }

    /**
     * Stocke une nouvelle valeur pour un attribut donné dans le stockage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'attribut_id' => 'required|exists:attributs,id', // Vérifie que l'attribut_id existe dans la table attributs
            'nom' => 'required|string|max:255',
        ]);

        // Création d'une nouvelle valeur
        $valeur = new Valeur();
        $valeur->attribut_id = $request->attribut_id;
        $valeur->nom = $request->nom;
        $valeur->save();

        // Retourner une réponse JSON avec un message de succès
        return response()->json([
            'message' => 'La valeur a été ajoutée avec succès.',
            'valeur' => $valeur
        ], 201); // Code 201 pour la création
    }

    /**
     * Affiche la ressource spécifiée.
     *
     * @param  \App\Models\Valeur  $valeur
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Valeur $valeur)
    {
        return response()->json(['valeur' => $valeur]);
    }

    /**
     * Met à jour la ressource spécifiée dans le stockage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Valeur  $valeur
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Valeur $valeur)
    {
        // Valider les données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        // Mettre à jour la valeur
        $valeur->update([
            'nom' => $request->nom,
        ]);

        // Retourner une réponse JSON avec un message de succès
        return response()->json([
            'message' => 'Valeur mise à jour avec succès.',
            'valeur' => $valeur
        ]);
    }

    /**
     * Supprime la ressource spécifiée du stockage.
     *
     * @param  \App\Models\Valeur  $valeur
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Valeur $valeur)
    {
        // Récupérer l'attribut associé à cette valeur
        $attribut = $valeur->attribut;

        // Supprimer la valeur
        $valeur->delete();

        // Retourner une réponse JSON avec un message de succès
        return response()->json([
            'message' => 'Valeur supprimée avec succès.',
            'attribut' => $attribut
        ]);
    }
    public function showValuesForAttribut($id)
    {
        // Récupérer l'attribut avec ses valeurs associées
        $attribut = Attribut::with('valeurs')->findOrFail($id);
        
        // Retourner les valeurs sous forme de JSON
        return response()->json(['valeurs' => $attribut->valeurs]);
    }
}
