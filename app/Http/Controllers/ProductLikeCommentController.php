<?php
namespace App\Http\Controllers;

use App\Models\ProductLikeComment;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductLikeCommentController extends Controller
{
    // Method to fetch all comments grouped by product
    public function index()
    {
        $commentsGrouped = ProductLikeComment::with('produit', 'client')
                            ->get()
                            ->groupBy('produit_id');
        
        $likeCounts = $commentsGrouped->map(function ($comments, $produitId) {
            return $comments->where('like', true)->count();
        });
    
        return response()->json([
            'comments' => $commentsGrouped,
            'likeCounts' => $likeCounts,
        ]);
    }

    // Method to create a new comment
    public function create()
    {
        return response()->json(['message' => 'Create a new comment'], 200);
    }

    // Method to show a single comment
    public function show(ProductLikeComment $productLikeComment)
    {
        return response()->json($productLikeComment);
    }

    // Method to get the like count for a product
    public function getLikesCount($produit_id)
    {
        $likeCount = ProductLikeComment::where('produit_id', $produit_id)
                                       ->where('like', true)
                                       ->count();
                                       
        return response()->json(['like_count' => $likeCount]);
    }

    // Method to edit a comment
    public function edit(ProductLikeComment $productLikeComment)
    {
        return response()->json(['message' => 'Edit the comment', 'data' => $productLikeComment]);
    }

    // Method to store a new comment
    public function store(Request $request)
    {
        /*if (!Auth::check()) {
            return response()->json(['error' => 'Vous devez être connecté pour ajouter un commentaire.'], 401);
        }*/
    
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
    
        $existingComment = ProductLikeComment::where('produit_id', $validated['produit_id'])
                                             ->where('client_id', 2)
                                             ->whereNotNull('commentaire')
                                             ->first();
    
        if ($existingComment) {
            return response()->json(['error' => 'Vous avez déjà commenté ce produit.'], 400);
        }
    
        $comment = new ProductLikeComment([
            'produit_id' => $validated['produit_id'],
            'client_id' =>2,
            'commentaire' => $validated['commentaire'],
            'image' => $photoName,
        ]);
    
        $comment->save();
    
        return response()->json(['message' => 'Commentaire ajouté avec succès.'], 201);
    }

    // Method to update a comment
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
    
        return response()->json(['message' => 'Commentaire mis à jour avec succès.']);
    }
    
    // Method to delete a comment
    public function destroy(ProductLikeComment $productLikeComment)
    {
        if ($productLikeComment->image) {
            \Storage::disk('public')->delete($productLikeComment->image);
        }
        $productLikeComment->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès.']);
    }

    // Method to like or dislike a product
    public function like(Request $request)
    {
        /*if (!Auth::check()) {
            return response()->json(['error' => 'Vous devez être connecté pour liker un produit.'], 401);
        }*/

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
        ]);

        $produit_id = $validated['produit_id'];
        $client_id = 2;

        $existingLike = ProductLikeComment::where('produit_id', $produit_id)
                                         ->where('client_id', $client_id)
                                         ->first();

        if ($existingLike) {
            if ($existingLike->like) {
                $existingLike->delete();
            } else {
                $existingLike->like = true;
                $existingLike->save();
            }
        } else {
            ProductLikeComment::create([
                'produit_id' => $produit_id,
                'client_id' => $client_id,
                'like' => true,
            ]);
        }

        return response()->json(['message' => 'Action effectuée avec succès.']);
    }
}
