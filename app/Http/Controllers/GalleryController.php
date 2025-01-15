<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Produit;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Affiche une liste des images de la galerie en format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $galleries = Gallery::with('produit')->get();
        return response()->json([
            'status' => 'success',
            'data' => $galleries
        ], 200);
    }

    /**
     * Affiche un formulaire pour créer une nouvelle image de galerie.
     * Dans un contexte API, cette méthode renverrait les produits disponibles pour l'association.
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
     * Stocke une nouvelle image dans la galerie et retourne une réponse JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'type' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('img'), $imageName);

        $gallery = Gallery::create([
            'produit_id' => $request->produit_id,
            'type' => $request->type,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Image ajoutée avec succès.',
            'data' => $gallery
        ], 201);
    }

    /**
     * Affiche une image spécifique de la galerie en format JSON.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Gallery $gallery)
    {
        return response()->json([
            'status' => 'success',
            'data' => $gallery
        ], 200);
    }

    /**
     * Affiche un formulaire pour éditer une image de la galerie.
     * Cette méthode renverra la ressource à modifier en JSON.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Gallery $gallery)
    {
        $produits = Produit::all();
        return response()->json([
            'status' => 'success',
            'data' => [
                'gallery' => $gallery,
                'produits' => $produits
            ]
        ], 200);
    }

    /**
     * Met à jour une image dans la galerie et renvoie une réponse JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'type' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gallery->produit_id = $request->produit_id;
        $gallery->type = $request->type;

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si nécessaire
            if ($gallery->image) {
                \File::delete(public_path('img/' . $gallery->image));
            }

            // Sauvegarder la nouvelle image
            $photo = $request->file('image');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('img'), $photoName);
            $gallery->image = $photoName;
        }

        $gallery->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Image mise à jour avec succès.',
            'data' => $gallery
        ], 200);
    }

    /**
     * Supprime une image de la galerie et renvoie une réponse JSON.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Gallery $gallery)
    {
        // Supprimer l'image de stockage si elle existe
        if ($gallery->image) {
            \File::delete(public_path('img/' . $gallery->image));
        }

        $gallery->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Image supprimée avec succès.'
        ], 200);
    }
}
