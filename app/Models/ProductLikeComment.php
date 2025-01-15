<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLikeComment extends Model
{
    use HasFactory;

    protected $fillable = ['produit_id', 'client_id', 'like', 'commentaire', 'image'];

    // Relations
    protected $table = 'product_likes_comments';

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
