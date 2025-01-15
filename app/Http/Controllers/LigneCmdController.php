<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LigneCmd;

class LigneCmdController extends Controller
{
    /**
     * Afficher la liste des lignes de commande.
     */
    public function index()
    {
        $lignesCommande = LigneCmd::all();
        return response()->json($lignesCommande);
    }

    /**
     * Afficher le formulaire de création d'une nouvelle ligne de commande.
     */
    public function create()
    {
        return response()->json(['message' => 'Formulaire de création d\'une nouvelle ligne de commande.']);
    }

    /**
     * Enregistrer une nouvelle ligne de commande.
     */
    public function store(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $ligneCmd = LigneCmd::create($request->all());

        return response()->json([
            'success' => 'Ligne de commande ajoutée avec succès.',
            'ligneCmd' => $ligneCmd
        ], 201);
    }

    /**
     * Afficher une ligne de commande spécifique.
     */
    public function show(LigneCmd $ligneCmd)
    {
        return response()->json($ligneCmd);
    }

    /**
     * Afficher le formulaire de modification d'une ligne de commande.
     */
    public function edit(LigneCmd $ligneCmd)
    {
        return response()->json(['message' => 'Formulaire de modification de la ligne de commande.', 'ligneCmd' => $ligneCmd]);
    }

    /**
     * Mettre à jour une ligne de commande existante.
     */
    public function update(Request $request, LigneCmd $ligneCmd)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $ligneCmd->update($request->all());

        return response()->json([
            'success' => 'Ligne de commande mise à jour avec succès.',
            'ligneCmd' => $ligneCmd
        ]);
    }

    /**
     * Supprimer une ligne de commande.
     */
    public function destroy(LigneCmd $ligneCmd)
    {
        $ligneCmd->delete();

        return response()->json(['success' => 'Ligne de commande supprimée avec succès.']);
    }
}
