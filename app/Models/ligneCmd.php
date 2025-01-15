<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ligneCmd extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id', 'commande_id',  'qtecmnd'

    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function commande()
    {
        return $this->belongsTo(commande::class);
    }
}