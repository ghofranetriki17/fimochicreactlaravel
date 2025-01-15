<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Attribut;
use App\Models\Gallery;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Retourne une liste des produits groupés par type.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $produitsParType = Produit::with('valeurs')->get()->groupBy('type');
        $galleries = Gallery::all();

        return response()->json([
            'produitsParType' => $produitsParType,
            'galleries' => $galleries
        ], 200);
    }

    /**
     * Retourne les attributs avec leurs valeurs pour la création.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $attributs = Attribut::with('valeurs')->get();

        return response()->json($attributs, 200);
    }

    /**
     * Stocke un produit.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'qte_dispo' => 'required|integer',
            'type' => 'nullable|string|max:255',
            'date_ajout' => 'nullable|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'attribute_values' => 'required|array',
        ]);

        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('img'), $photoName);
        } else {
            $photoName = null;
        }

        $produit = new Produit();
        $produit->name = $request->name;
        $produit->prix = $request->prix;
        $produit->qte_dispo = $request->qte_dispo;
        $produit->type = $request->type;
        $produit->date_ajout = $request->date_ajout;
        $produit->description = $request->description;
        $produit->image = $photoName;
        $produit->save();

        foreach ($request->attribute_values as $attribut_id => $valeur_ids) {
            foreach ($valeur_ids as $valeur_id) {
                $produit->valeurs()->attach($valeur_id);
            }
        }

        return response()->json(['message' => 'Produit créé avec succès.', 'produit' => $produit], 201);
    }

    /**
     * Retourne un produit spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $produit = Produit::with(['valeurs', 'galleries'])->findOrFail($id);

        return response()->json($produit, 200);
    }

    /**
     * Met à jour un produit.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'qte_dispo' => 'nullable|integer|min:0',
            'prix' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'date_ajout' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $produit = Produit::findOrFail($id);

        $produit->fill($request->only(['name', 'qte_dispo', 'prix', 'description', 'type', 'date_ajout']));

        if ($request->hasFile('image')) {
            if ($produit->image) {
                \File::delete(public_path('img/' . $produit->image));
            }
            $photo = $request->file('image');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('img'), $photoName);
            $produit->image = $photoName;
        }

        $produit->save();

        return response()->json(['message' => 'Produit mis à jour avec succès.', 'produit' => $produit], 200);
    }

    /**
     * Supprime un produit.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return response()->json(['message' => 'Produit supprimé avec succès.'], 200);
    }

    /**
     * Recherche des produits.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Produit::where('name', 'like', "%{$query}%")->get();

        return response()->json($products, 200);
    }
}
