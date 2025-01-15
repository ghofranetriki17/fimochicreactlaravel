<?php

namespace App\Http\Controllers\API;

use App\Models\ProductLikeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProductLikeCommentController extends Controller
{
    // Récupère tous les commentaires groupés par produit
    public function index()
    {
        $commentsGrouped = ProductLikeComment::with('produit', 'client')
                            ->get()
                            ->groupBy('produit_id');
        
        $likeCounts = $commentsGrouped->map(function ($comments, $produitId) {
            return $comments->where('like', true)->count();
        });

        return response()->json([
            'commentsGrouped' => $commentsGrouped,
            'likeCounts' => $likeCounts,
        ], 200);
    }

    // Récupère les détails d'un commentaire spécifique
    public function show(ProductLikeComment $productLikeComment)
    {
        return response()->json([
            'productLikeComment' => $productLikeComment,
        ], 200);
    }

    // Compte le nombre de likes pour un produit spécifique
    public function getLikesCount($produit_id)
    {
        $likesCount = ProductLikeComment::where('produit_id', $produit_id)
                                        ->where('like', true)
                                        ->count();

        return response()->json([
            'likesCount' => $likesCount,
        ], 200);
    }

    // Ajoute un nouveau commentaire
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Vous devez être connecté pour ajouter un commentaire.',
            ], 401);
        }

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'commentaire' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoName = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('img'), $photoName);
        }

        // Vérifie si l'utilisateur a déjà commenté ce produit
        $existingComment = ProductLikeComment::where('produit_id', $validated['produit_id'])
                                             ->where('client_id', Auth::id())
                                             ->whereNotNull('commentaire')
                                             ->first();

        if ($existingComment) {
            return response()->json([
                'error' => 'Vous avez déjà commenté ce produit.',
            ], 400);
        }

        $comment = new ProductLikeComment([
            'produit_id' => $validated['produit_id'],
            'client_id' => Auth::id(),
            'commentaire' => $validated['commentaire'],
            'image' => $photoName,
        ]);

        $comment->save();

        return response()->json([
            'message' => 'Votre commentaire a bien été ajouté.',
            'comment' => $comment,
        ], 201);
    }

    // Met à jour un commentaire existant
    public function update(Request $request, ProductLikeComment $productLikeComment)
    {
        $validated = $request->validate([
            'commentaire' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productLikeComment->update($validated);

        if ($request->hasFile('image')) {
            if ($productLikeComment->image) {
                \Storage::disk('public')->delete($productLikeComment->image);
            }
            $imagePath = $request->file('image')->store('comment_images', 'public');
            $productLikeComment->image = $imagePath;
        }

        $productLikeComment->save();

        return response()->json([
            'message' => 'Votre commentaire a bien été mis à jour.',
            'productLikeComment' => $productLikeComment,
        ], 200);
    }

    // Supprime un commentaire
    public function destroy(ProductLikeComment $productLikeComment)
    {
        if ($productLikeComment->image) {
            \Storage::disk('public')->delete($productLikeComment->image);
        }
        $productLikeComment->delete();

        return response()->json([
            'message' => 'Commentaire supprimé avec succès.',
        ], 200);
    }

    // Aime ou n'aime pas un produit
    public function like(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Vous devez être connecté pour liker un produit.',
            ], 401);
        }

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
        ]);

        $produit_id = $validated['produit_id'];
        $client_id = Auth::id();

        // Vérifie si l'utilisateur a déjà liké ce produit
        $existingLike = ProductLikeComment::where('produit_id', $produit_id)
                                         ->where('client_id', $client_id)
                                         ->first();

        if ($existingLike) {
            // Si déjà liké, désaimez
            if ($existingLike->like) {
                $existingLike->delete();
            } else {
                $existingLike->like = true;
                $existingLike->save();
            }
        } else {
            // Sinon, aimez
            ProductLikeComment::create([
                'produit_id' => $produit_id,
                'client_id' => $client_id,
                'like' => true,
            ]);
        }

        return response()->json([
            'message' => 'Votre action a bien été prise en compte.',
        ], 200);
    }
}
