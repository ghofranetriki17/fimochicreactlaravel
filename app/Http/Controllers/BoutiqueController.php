<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Gallery;
use App\Models\Attribut;
use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoutiqueController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les produits et les grouper par type
        $produitsGroupedByType = Produit::with(['promotions', 'galleries', 'valeurs'])
            ->get()
            ->groupBy('type'); // Utilisation de `groupBy` pour regrouper les produits par type

        // Récupérer d'autres données nécessaires
        $attributs = Attribut::with('valeurs')->get();
        $minPrice = Produit::min('prix');
        $maxPrice = Produit::max('prix');
        $galleries = Gallery::all();
        $types = Produit::distinct()->pluck('type');

        // Retourner une réponse JSON
        return response()->json([
            'produits' => $produitsGroupedByType,
            'galleries' => $galleries,
            'attributs' => $attributs,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'types' => $types,
        ]);
    }
}
