<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Valeur;
use App\Models\ProduitValeur;
use Illuminate\Http\Request;

class ProduitValeurController extends Controller
{
    /**
     * Affiche les valeurs associées à un produit donné.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Produit $produit)
    {
        // Récupérer toutes les valeurs associées à ce produit
        $valeurs = $produit->valeurs()->orderBy('created_at', 'DESC')->get();

        // Retourner une réponse JSON avec les valeurs
        return response()->json([
            'produit' => $produit,
            'valeurs' => $valeurs
        ]);
    }

    /**
     * Affiche le formulaire de création d'une nouvelle association produit-valeur.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Produit $produit)
    {
        // Récupérer toutes les valeurs disponibles pour l'association
        $valeurs = Valeur::all();

        return response()->json([
            'produit' => $produit,
            'valeurs' => $valeurs
        ]);
    }

    /**
     * Associe une nouvelle valeur à un produit dans le stockage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'valeur_id' => 'required|exists:valeurs,id',
            'prix' => 'required|numeric',
        ]);

        // Créer une nouvelle entrée dans la table pivot produit_valeurs
        $produitValeur = ProduitValeur::create([
            'produit_id' => $request->produit_id,
            'valeur_id' => $request->valeur_id,
            'prix' => $request->prix,
        ]);

        // Retourner une réponse JSON de succès
        return response()->json([
            'success' => 'Valeur associée avec succès au produit',
            'produit_valeur' => $produitValeur
        ]);
    }

    /**
     * Dissocier une valeur d'un produit.
     *
     * @param  \App\Models\Produit  $produit
     * @param  \App\Models\Valeur   $valeur
     * @return \Illuminate\Http\JsonResponse
     */
    public function detach(Produit $produit, Valeur $valeur)
    {
        // Dissocier la valeur du produit (sans supprimer la valeur elle-même)
        $produit->valeurs()->detach($valeur->id);
    
        // Retourner une réponse JSON de succès
        return response()->json([
            'success' => 'Valeur dissociée avec succès du produit',
            'produit_id' => $produit->id,
            'valeur_id' => $valeur->id
        ]);
    }
    
}
