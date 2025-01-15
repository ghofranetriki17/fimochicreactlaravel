<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'product_id',
        'new_price'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'product_id');
    }
}
