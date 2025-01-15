<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Display a listing of the FAQs
    public function index()
    {
        try {
            $faqs = Faq::all()->groupBy('type_de_question');
            $topFaqs = Faq::orderBy('likes_count', 'desc')->take(3)->get();
            return response()->json(['faqs' => $faqs, 'topFaqs' => $topFaqs]);
        } catch (\Exception $e) {
            return response()->json("Problème de récupération de la liste des FAQ.");
        }
    }

    // Like an FAQ
    public function like(Faq $faq)
    {
        try {
            $faq->increment('likes_count');
            return response()->json($faq);
        } catch (\Exception $e) {
            return response()->json("Problème lors de l'ajout du like.");
        }
    }

    // Display the FAQs for the admin to manage
    public function manage()
    {
        try {
            $faqs = Faq::all();
            return response()->json($faqs);
        } catch (\Exception $e) {
            return response()->json("Problème de récupération des FAQ pour gestion.");
        }
    }

    // Store a newly created FAQ
    public function store(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'type_de_question' => 'required|string|max:255',
                'question' => 'required|string',
                'reponse' => 'required|string',
            ]);
        
            // Traitement du fichier vidéo
            $videoName = null;
            if ($request->hasFile('video')) {
                $videoFile = $request->file('video');
                $videoName = time() . '_video.' . $videoFile->getClientOriginalExtension();
                $videoFile->move(public_path('img'), $videoName);
            }
        
            // Création de la FAQ
            $faq = Faq::create([
                'type_de_question' => $validated['type_de_question'],
                'question' => $validated['question'],
                'reponse' => $validated['reponse'],
                'video_url' => 'img/' . $videoName
            ]);
        
            return response()->json($faq);
        } catch (\Exception $e) {
            return response()->json("Problème lors de l'ajout de la FAQ.");
        }
    }

    // Display the specified FAQ
    public function show($id)
    {
        try {
            $faq = Faq::findOrFail($id);
            return response()->json($faq);
        } catch (\Exception $e) {
            return response()->json("Problème de récupération des données de la FAQ.");
        }
    }

    // Update the specified FAQ
    public function update(Request $request, Faq $faq)
    {
        try {
            $request->validate([
                'type_de_question' => 'required|string|max:255',
                'question' => 'required|string',
                'reponse' => 'required|string',
                'video_url' => 'nullable|url',
            ]);
        
            $faq->update($request->all());
            return response()->json($faq);
        } catch (\Exception $e) {
            return response()->json("Problème de modification de la FAQ.");
        }
    }

    // Remove the specified FAQ
    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();
            return response()->json("FAQ supprimée avec succès.");
        } catch (\Exception $e) {
            return response()->json("Problème de suppression de la FAQ.");
        }
    }
}
