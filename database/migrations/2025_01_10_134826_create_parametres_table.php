<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametresTable extends Migration
{
    public function up()
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Identifiant unique pour chaque paramètre
            $table->string('value')->nullable(); // Valeur du paramètre
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parametres');
    }
}

