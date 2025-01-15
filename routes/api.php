<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CategorieController, FaqController, ProduitController, ClientController, 
    ProduitValeurController, ValeurController, AttributController, RessourceController,
    GalleryController, PanierController, PromotionController, CommandeController, 
    RessourcePersonnalisationController, CommandePersonnaliseeController, 
    ProductLikeCommentController, ContactController, PromoCodeController, ParametreController
};

// Routes API pour les produits, clients, etc.
Route::middleware('api')->group(function () {
    // Routes for categories
    Route::resource('categories', CategorieController::class);

    // Add routes for FAQ
    Route::resource('faqs', FaqController::class);
    Route::post('faqs/{faq}/like', [FaqController::class, 'like'])->name('faqs.like');

    // Add routes for products
    Route::resource('produits', ProduitController::class);

    // Additional routes for clients, attributes, values, etc.
    Route::resource('clients', ClientController::class);
    Route::resource('valeurs', ValeurController::class);
    Route::resource('produitvaleur', ProduitValeurController::class);
    Route::resource('attributs', AttributController::class);
    Route::resource('ressources', RessourceController::class);
    Route::resource('galleries', GalleryController::class);
    Route::resource('panier', PanierController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('commandes', CommandeController::class);
    Route::resource('ressources_personnalisation', RessourcePersonnalisationController::class);
    Route::resource('commandespersoonalisse', CommandePersonnaliseeController::class);
   // Route::resource('product_like_comments', ProductLikeCommentController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('promo_codes', PromoCodeController::class);
    Route::resource('parametres', ParametreController::class);
});
