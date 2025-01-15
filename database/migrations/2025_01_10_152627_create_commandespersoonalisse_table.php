<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandespersoonalisseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commandespersoonalisse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('image_reelle');
            $table->string('image_perso');
            $table->dateTime('commande_date');
            $table->text('note')->nullable();
            $table->decimal('prix_total', 8, 2);
            $table->string('adresse');
            $table->string('methode_paiement');
            $table->timestamps();
            
            // Foreign key constraint
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
        Schema::dropIfExists('commandespersoonalisse');
    }
}
