<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanierTable extends Migration
{
    /**
     * Exécute la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panier', function (Blueprint $table) {
            $table->id(); // Identifiant unique pour chaque entrée dans le panier
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Clé étrangère vers la table clients
            $table->foreignId('produit_id')->constrained()->onDelete('cascade'); // Clé étrangère vers la table produits
            $table->integer('quantite'); // Quantité du produit
            $table->timestamps(); // Horodatage pour la création et la mise à jour
        });
    }

    /**
     * Annule la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('panier');
    }
}
