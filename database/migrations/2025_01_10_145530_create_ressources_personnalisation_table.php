<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRessourcesPersonnalisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ressources_personnalisation', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // ex: 'couleur', 'forme', 'pendentif', etc.
            $table->string('nom'); // ex: 'rouge', 'cercle', 'coeur', etc.
            $table->string('image'); // chemin de l'image
            $table->string('cat'); // chemin de l'image

            $table->decimal('prix', 8, 2); // prix de la ressource
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
        Schema::dropIfExists('ressources_personnalisation');
    }
}
