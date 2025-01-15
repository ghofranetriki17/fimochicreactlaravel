<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RessourcePersonnalisation;
use App\Models\Panier;
use App\Models\ClientRessourcePersonnalisation;

class ClientRessourcePersonnalisationController extends Controller
{
    /**
     * Récupère les ressources de personnalisation regroupées par type pour les boucles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexBoucles()
    {
        $ressourcesParType = RessourcePersonnalisation::where('cat', 'boucles')->get()->groupBy('type');
        return response()->json([
            'status' => 'success',
            'data' => $ressourcesParType
        ], 200);
    }

    /**
     * Récupère les ressources de personnalisation regroupées par type pour les cadeaux, avec le panier du client.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexCadeau()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous devez être connecté pour personnaliser.'
            ], 401);
        }

        $ressourcesParType = RessourcePersonnalisation::where('cat', 'cadeau')->get()->groupBy('type');

        $clientId = Auth::user()->client->id;
        $cart = Panier::where('client_id', $clientId)
            ->with(['produit.galleries' => function($query) {
                $query->where('type', 'sans');
            }])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'ressources' => $ressourcesParType,
                'cart' => $cart
            ]
        ], 200);
    }

    /**
     * Enregistre les personnalisations du client et renvoie une réponse JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'ressources_json' => 'required|json',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous devez être connecté pour personnaliser.'
            ], 401);
        }

        $clientId = Auth::user()->client->id;
        $ressourcesData = json_decode($request->input('ressources_json'), true);

        foreach ($ressourcesData as $data) {
            ClientRessourcePersonnalisation::create([
                'client_id' => $clientId,
                'ressource_personnalisation_id' => $data['id'],
                'quantite' => $data['quantity'],
                'prix_total' => $data['quantity'] * RessourcePersonnalisation::find($data['id'])->prix,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Personnalisation enregistrée avec succès.'
        ], 201);
    }

    /**
     * Récupère et regroupe les personnalisations des clients pour le dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDashboard()
    {
        $personnalisations = ClientRessourcePersonnalisation::with(['client', 'ressourcePersonnalisation'])
            ->orderBy('created_at')
            ->get()
            ->groupBy(['client_id', function ($item) {
                return $item->created_at->format('Y-m-d H:i:s'); 
            }]);

        return response()->json([
            'status' => 'success',
            'data' => $personnalisations
        ], 200);
    }

    /**
     * Met à jour la quantité d'une personnalisation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQuantity(Request $request, $id)
    {
        $clientRessourcePersonnalisation = ClientRessourcePersonnalisation::findOrFail($id);
        $quantite = $request->input('quantite');
        $clientRessourcePersonnalisation->quantite = $quantite;
        $clientRessourcePersonnalisation->prix_total = $clientRessourcePersonnalisation->ressourcePersonnalisation->prix * $quantite;
        $clientRessourcePersonnalisation->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Quantité mise à jour avec succès.',
            'data' => $clientRessourcePersonnalisation
        ], 200);
    }

    /**
     * Supprime toutes les personnalisations par date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllByDate(Request $request, $date)
    {
        $date = date('Y-m-d', strtotime($date));

        ClientRessourcePersonnalisation::whereDate('created_at', $date)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Toutes les personnalisations de cette date ont été supprimées.'
        ], 200);
    }

    /**
     * Récupère toutes les personnalisations d'un client.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $clientId = Auth::user()->client->id;
        $personnalisations = ClientRessourcePersonnalisation::where('client_id', $clientId)->get();
        return response()->json([
            'status' => 'success',
            'data' => $personnalisations
        ], 200);
    }

    /**
     * Supprime une personnalisation spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $clientRessourcePersonnalisation = ClientRessourcePersonnalisation::findOrFail($id);
        $clientRessourcePersonnalisation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Personnalisation supprimée avec succès.'
        ], 200);
    }
}
