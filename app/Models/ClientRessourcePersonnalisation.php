<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRessourcePersonnalisation extends Model
{
    use HasFactory;
    protected $table = 'client_ressource_personnalisation';

    protected $fillable = [
        'client_id',
        'ressource_personnalisation_id',
        'quantite',
        'prix_total',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function ressourcePersonnalisation()
    {
        return $this->belongsTo(RessourcePersonnalisation::class);
    }
}
