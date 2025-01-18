<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Commande;
use App\Models\Panier;
use App\Models\PromoCode;
use App\Models\LigneCmd;
use App\Models\Produit;

class CommandeController extends Controller
{
    // Méthode pour afficher les commandes du client connecté
    public function index()
    {
        // Vérifiez si l'utilisateur est connecté
        /*if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez vous connecter pour voir vos commandes.'], 401);
        }

        // Obtenez l'utilisateur connecté
        $user = Auth::user();

        // Vérifiez si l'utilisateur est un administrateur
        if ($user->type == 1) { // Remplacez 'is_admin' par la condition ou le champ qui indique si l'utilisateur est un admin
            // Récupérez toutes les commandes pour les administrateurs
            $commandes = Commande::with('lignesCommande')->get();
        } else {*/
            // Récupérez les commandes du client connecté pour les clients
            #$clientId = $user->client->id;
            $clientId =2;

            $commandes = Commande::where('client_id', $clientId)->with('lignesCommande')->get();
       # }

        // Retourner les données sous forme de JSON
        return response()->json($commandes);
    }

    // Méthode pour créer une nouvelle commande
    public function store(Request $request)
    {
        /*if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez vous connecter pour passer une commande.'], 401);
        }
*/
       # $clientId = Auth::user()->client->id;
       $clientId=2;
        $panier = Panier::where('client_id', $clientId)->get();

        if ($panier->isEmpty()) {
            return response()->json(['message' => 'Votre panier est vide.'], 400);
        }

        $validated = $request->validate([
            'adresse' => 'required|string|max:255',
            'payment_method' => 'required|in:cash_on_delivery,post,visa',
            'total_price' => 'required|numeric',
            'promo_code' => 'nullable|string',
        ]);

        $totalPrice = $validated['total_price'];

        // Vérification du code promo s'il est entré
        if ($request->filled('promo_code')) {
            $promoCode = PromoCode::where('code', $validated['promo_code'])
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($promoCode) {
                $discount = ($promoCode->percentage / 100) * $totalPrice;
                $totalPrice -= $discount;
            } else {
                return response()->json(['error' => 'Le code promo est invalide ou a expiré.'], 400);
            }
        }

        // Vérifier si c'est le premier achat du client
        $isFirstPurchase = !Commande::where('client_id', $clientId)->exists();
        if ($isFirstPurchase) {
            $discount = $totalPrice * 0.20; // Réduction de 20% pour le premier achat
            $totalPrice -= $discount;
        }

        // Création de la commande
        $commande = new Commande();
        $commande->client_id = $clientId;
        $commande->adresse = $validated['adresse'];
        $commande->mode_paiement = $validated['payment_method'];
        $commande->prix = $totalPrice; // Prix après réduction
        $commande->date_cmd = now();
        $commande->date_estimee_liv = now()->addDays(7);
        $commande->etat = '0';
        $commande->save();

        // Création des lignes de commande et mise à jour des quantités de produits
        foreach ($panier as $item) {
            $produit = Produit::find($item->produit_id);
            $produit->qte_dispo -= $item->quantite;
            $produit->save();

            $ligneCommande = new LigneCmd();
            $ligneCommande->commande_id = $commande->id;
            $ligneCommande->produit_id = $produit->id;
            $ligneCommande->qtecmnd = $item->quantite;
            $ligneCommande->save();
        }

        // Vider le panier
        Panier::where('client_id', $clientId)->delete();

        return response()->json([
            'success' => 'Votre commande a été passée avec succès !' . ($isFirstPurchase ? ' Félicitations pour votre premier achat ! Vous avez bénéficié d\'une réduction de 20%.' : '')
        ], 200);
    }

    // Méthode pour afficher les détails d'une commande
    public function show($id)
    {
        $commande = Commande::with('lignesCommande.produit')->find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande introuvable.'], 404);
        }

        return response()->json($commande);
    }

    // Méthode pour afficher les commandes dans le tableau de bord admin
    public function adminIndex()
    {
        $commandes = Commande::with('client')->paginate(10); // Ajoutez la pagination si nécessaire

        return response()->json($commandes);
    }

    // Méthode pour modifier une commande
    public function edit($id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande introuvable.'], 404);
        }

        return response()->json($commande);
    }

    // Méthode pour afficher les détails d'une commande (alternative)
    public function details($id)
    {
        $commande = Commande::with('lignesCommande.produit')->find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande introuvable.'], 404);
        }

        return response()->json($commande);
    }

    // Méthode pour mettre à jour une commande
    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'date_estimee_liv' => 'required|date',
            'etat' => 'required|in:0,1,2,3,4',
        ]);

        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande introuvable.'], 404);
        }

        // Mettre à jour les informations de la commande
        $commande->date_estimee_liv = $validated['date_estimee_liv'];
        $commande->etat = $validated['etat'];
        $commande->save();

        return response()->json(['success' => 'Commande mise à jour avec succès !']);
    }
}
