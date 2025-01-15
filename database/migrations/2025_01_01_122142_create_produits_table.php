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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->float('prix')->nullable();
            $table->integer('qte_dispo')->nullable();
            $table->string('type')->nullable();
            $table->date('date_ajout')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();


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
        Schema::dropIfExists('produits');
    }
};
