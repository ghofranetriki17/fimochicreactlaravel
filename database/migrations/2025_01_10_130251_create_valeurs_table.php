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
        Schema::create('valeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->unsignedBigInteger('attribut_id')->nullable(); // Utilisation d'un unsigned big integer pour la clé étrangère
            $table->foreign('attribut_id')->references('id')->on('attributs')->onDelete('cascade');
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
        Schema::dropIfExists('valeurs');
    }
};
