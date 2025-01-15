<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Panier; 
use App\Models\ClientRessourcePersonnalisation; 
use App\Models\CommandePersonnalisee; 
use App\Models\Produit; 

class PanierController extends Controller
{
    /**
     * Afficher le panier du client connecté.
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez vous connecter pour voir votre panier.'], 401);
        }
    
        $clientId = Auth::user()->client->id;
    
        $cart = Panier::where('client_id', $clientId)->with('produit')->get();
    
        $total = $cart->sum(function ($item) {
            return $item->quantite * $item->getPrix();
        });
        
        $personnalisations = ClientRessourcePersonnalisation::where('client_id', $clientId)
            ->with('ressourcePersonnalisation')
            ->orderBy('created_at')
            ->get()
            ->groupBy(['created_at' => function ($item) {
                return $item->created_at->format('Y-m-d H:i:s'); 
            }]);
        
        $commandes = CommandePersonnalisee::where('client_id', $clientId)
            ->with('client')
            ->get();
        
        return response()->json([
            'cart' => $cart,
            'personnalisations' => $personnalisations,
            'commandes' => $commandes,
            'total' => $total
        ]);
    }
    
    /**
     * Ajouter ou mettre à jour un produit dans le panier.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez vous connecter pour ajouter des produits au panier.'], 401);
        }

        $clientId = Auth::user()->client->id;
    
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $produitId = $request->input('produit_id');
        $quantite = $request->input('quantite');

        Panier::updateOrCreate(
            ['client_id' => $clientId, 'produit_id' => $produitId],
            ['quantite' => \DB::raw('quantite + ' . $quantite)]
        );

        return response()->json(['success' => 'Produit ajouté au panier avec succès.']);
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier.
     */
    public function update(Request $request, Panier $panier)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez vous connecter pour mettre à jour votre panier.'], 401);
        }

        $request->validate([
            'action' => 'required|string|in:increment,decrement',
        ]);

        if ($request->action === 'increment') {
            $panier->quantite += 1;  
        } elseif ($request->action === 'decrement') {
            $panier->quantite = max(1, $panier->quantite - 1);  
        }

        $panier->save();

        return response()->json(['success' => 'Quantité mise à jour avec succès.']);
    }

    /**
     * Supprimer un produit du panier.
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez vous connecter pour gérer votre panier.'], 401);
        }
    
        $clientId = Auth::user()->client->id;
    
        $panierItem = Panier::where('client_id', $clientId)->where('id', $id)->first();
    
        if (!$panierItem) {
            return response()->json(['message' => 'Article non trouvé dans votre panier.'], 404);
        }
    
        $panierItem->delete();
    
        return response()->json(['success' => 'Produit supprimé du panier avec succès.']);
    }
}
