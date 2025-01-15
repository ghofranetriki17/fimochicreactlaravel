<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'prix', 'mode_paiement', 'adresse', 'date_cmd', 'date_estimee_liv', 'etat',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lignesCommande()
    {
        return $this->hasMany(ligneCmd::class, 'commande_id');
    }
}
