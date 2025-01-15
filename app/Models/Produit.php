<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model 
{
    use HasFactory;

    protected $fillable = [
        'name', 'prix', 'qte_dispo', 'type', 'date_ajout', 'description', 'image',
    ];

    public function valeurs()
    {
        return $this->belongsToMany(Valeur::class, 'produit_valeurs');
    }

    public function ligneCmds()
    {
        return $this->hasMany(LigneCmd::class);
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'product_id');
    }
    public function paniers()
    {
        return $this->hasMany(Panier::class);
    }
    public function comments()
    {
        return $this->hasMany(ProductLikeComment::class, 'produit_id');
    }

    public function likes()
    {
        return $this->hasMany(ProductLikeComment::class, 'produit_id');
    }
    public function getLikesCountAttribute()
    {
        // Assuming 'likes' is a relationship method returning all likes
        return $this->likes->where('like', true)->count();
    }
    
    public function productLikeComments()
    {
        return $this->hasMany(ProductLikeComment::class);
    }

   
    public function userHasLiked($userId)
    {
        return $this->productLikeComments()->where('client_id', $userId)->where('like', true)->exists();
    }



    
    
}
