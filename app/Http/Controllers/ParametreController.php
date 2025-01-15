<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    /**
     * Afficher la liste des paramètres.
     */
    public function index()
    {
        $parametres = Parametre::all();
        return response()->json(['parametres' => $parametres]);
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        // Si vous utilisez Vue.js, vous pourriez renvoyer un statut ici.
        return response()->json(['message' => 'Créer un paramètre']);
    }

    /**
     * Ajouter un nouveau paramètre.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:parametres',
            'value' => 'required',
        ]);

        $parametre = Parametre::create($request->all());

        return response()->json([
            'success' => 'Paramètre créé avec succès',
            'parametre' => $parametre
        ]);
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Parametre $parametre)
    {
        return response()->json(['parametre' => $parametre]);
    }

    /**
     * Mettre à jour un paramètre.
     */
    public function update(Request $request, Parametre $parametre)
    {
        $request->validate([
            'key' => 'required|unique:parametres,key,' . $parametre->id,
            'value' => 'required',
        ]);

        $parametre->update($request->all());

        return response()->json([
            'success' => 'Paramètre mis à jour avec succès',
            'parametre' => $parametre
        ]);
    }

    /**
     * Supprimer un paramètre.
     */
    public function destroy(Parametre $parametre)
    {
        $parametre->delete();

        return response()->json(['success' => 'Paramètre supprimé avec succès']);
    }
}
