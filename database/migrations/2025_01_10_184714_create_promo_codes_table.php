<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodesTable extends Migration
{
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('percentage', 5, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
}
