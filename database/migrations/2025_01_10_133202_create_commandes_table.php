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
        Schema::create('commandes', function (Blueprint $table) {
           

            $table->id();
            $table->float('prix')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->string('adresse')->nullable();
            $table->date('date_cmd');
            $table->date('date_estimee_liv');
            $table->integer('etat');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commandes');
    }
};
