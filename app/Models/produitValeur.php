<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitValeur extends Model
{
    protected $table = 'produit_valeurs'; // Nom de la table pivot

    protected $fillable = [
        'produit_id', 'valeur_id'
    ];

    // Relation avec le produit
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Relation avec la valeur
    public function valeur()
    {
        return $this->belongsTo(Valeur::class);
    }
}
