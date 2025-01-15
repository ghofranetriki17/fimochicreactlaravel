<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Produit;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Affiche la liste des promotions en format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $promotions = Promotion::with('produit')->get();
        return response()->json([
            'status' => 'success',
            'data' => $promotions
        ], 200);
    }

    /**
     * Affiche la liste des produits disponibles pour la création d'une promotion en format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $produits = Produit::all();
        return response()->json([
            'status' => 'success',
            'data' => $produits
        ], 200);
    }

    /**
     * Stocke une nouvelle promotion et renvoie une réponse JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produits,id',
            'new_price' => 'required|numeric',
        ]);

        $promotion = Promotion::create([
            'product_id' => $request->product_id,
            'new_price' => $request->new_price,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Promotion ajoutée avec succès.',
            'data' => $promotion
        ], 201);
    }

    /**
     * Affiche les informations d'une promotion spécifique en format JSON.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Promotion $promotion)
    {
        return response()->json([
            'status' => 'success',
            'data' => $promotion
        ], 200);
    }

    /**
     * Met à jour une promotion et renvoie une réponse JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'product_id' => 'required|exists:produits,id',
            'new_price' => 'required|numeric',
        ]);

        $promotion->update([
            'product_id' => $request->product_id,
            'new_price' => $request->new_price,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Promotion mise à jour avec succès.',
            'data' => $promotion
        ], 200);
    }

    /**
     * Supprime une promotion et renvoie une réponse JSON.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Promotion supprimée avec succès.'
        ], 200);
    }
}
