<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandePersonnalisee extends Model
{
    use HasFactory;

    protected $table = 'commandespersoonalisse';

    protected $fillable = [
        'client_id',
        'image_reelle',
        'image_perso',
        'commande_date',
        'note',
        'prix_total',
        'adresse',
        'methode_paiement',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
