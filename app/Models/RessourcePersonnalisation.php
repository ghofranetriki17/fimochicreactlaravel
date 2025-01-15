<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RessourcePersonnalisation extends Model
{
    use HasFactory;

    protected $table = 'ressources_personnalisation';

    protected $fillable = [
        'type', 'nom', 'image', 'prix','cat'
    ];
    public function clientRessourcePersonnalisations()
    {
        return $this->hasMany(ClientRessourcePersonnalisation::class);
    }
}
