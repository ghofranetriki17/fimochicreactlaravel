<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RessourceController extends Controller
{
    /**
     * Affiche une liste des ressources en format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $ressources = Ressource::orderBy('created_at', 'DESC')->get();
        return response()->json([
            'status' => 'success',
            'data' => $ressources
        ], 200);
    }

    /**
     * Affiche une ressource spécifique en format JSON.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ressource = Ressource::find($id);

        if (!$ressource) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ressource non trouvée.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ressource
        ], 200);
    }

    /**
     * Stocke une nouvelle ressource dans la base de données en format JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'quantite' => 'required|integer',
            'couleur' => 'required|string',
            'type' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoName = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('img'), $photoName);
        }

        // Créer la ressource
        $ressource = Ressource::create([
            'nom' => $request->nom,
            'quantite' => $request->quantite,
            'couleur' => $request->couleur,
            'type' => $request->type,
            'image' => $photoName, // Assigner le nom du fichier si présent
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ressource ajoutée avec succès.',
            'data' => $ressource
        ], 201);
    }

    /**
     * Met à jour une ressource spécifiée en format JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer',
            'couleur' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ressource = Ressource::find($id);

        if (!$ressource) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ressource non trouvée.'
            ], 404);
        }

        $ressource->nom = $request->nom;
        $ressource->quantite = $request->quantite;
        $ressource->couleur = $request->couleur;
        $ressource->type = $request->type;

        // Gérer l'image si elle est mise à jour
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si nécessaire
            if ($ressource->image) {
                Storage::delete('img/' . $ressource->image);
            }
            // Enregistrer la nouvelle image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $ressource->image = $imageName;
        }

        $ressource->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Ressource mise à jour avec succès.',
            'data' => $ressource
        ], 200);
    }

    /**
     * Supprime une ressource spécifiée en format JSON.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ressource = Ressource::find($id);

        if (!$ressource) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ressource non trouvée.'
            ], 404);
        }

        // Supprimer l'image associée si elle existe dans le stockage
        if ($ressource->image) {
            Storage::disk('public')->delete($ressource->image);
        }

        // Supprimer la ressource de la base de données
        $ressource->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ressource supprimée avec succès.'
        ], 200);
    }
}
