<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prenom',
        'nom',
        'age',
        'numeroTel',
        'gender',
        'adresse',
    ];

    // Automatically set the 'prenom' attribute using the 'name' from User model
    public static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            $user = User::find($client->user_id);
            if ($user) {
                $client->prenom = $user->name;
            }
        });
    }
    public function paniers()
    {
        return $this->hasMany(Panier::class);
    }
    // Define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
    public function ressourcesPersonnalisation()
    {
        return $this->hasMany(ClientRessourcePersonnalisation::class);
    }
    public function likesComments()
{
    return $this->hasMany(ProductLikeComment::class);
}
}
