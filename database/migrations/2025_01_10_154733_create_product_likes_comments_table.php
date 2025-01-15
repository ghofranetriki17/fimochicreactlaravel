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
        Schema::create('product_likes_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('client_id');
            $table->boolean('like')->default(false);
            $table->text('commentaire')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
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
        Schema::dropIfExists('product_likes_comments');
    }
};
