<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Parametre;

class ParametreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Récupérer les paramètres depuis la base de données
        $parametres = Parametre::pluck('value', 'key')->toArray();

        // Partager les paramètres avec toutes les vues
        View::share('parametres', $parametres);
    }

    public function register()
    {
        //
    }
}
