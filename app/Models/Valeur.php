<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'attribut_id'
    ];

    public function attribut()
    {
        return $this->belongsTo(Attribut::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Valeur::class, 'produit_valeurs')
       ;    }
}
