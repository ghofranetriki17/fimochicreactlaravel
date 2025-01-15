<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produit_valeurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('valeur_id');
           

            // Ajoutez vos clés étrangères ici
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
            $table->foreign('valeur_id')->references('id')->on('valeurs')->onDelete('cascade');

            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produit_valeurs');
    }
};
