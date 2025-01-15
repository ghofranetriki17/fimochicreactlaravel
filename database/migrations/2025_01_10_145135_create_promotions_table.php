<?php
// database/migrations/xxxx_xx_xx_create_promotions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->decimal('new_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
