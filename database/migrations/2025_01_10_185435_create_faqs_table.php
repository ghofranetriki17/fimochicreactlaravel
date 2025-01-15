<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('type_de_question');
            $table->text('question');
            $table->text('reponse');
            $table->text('video_url')->nullable();
            $table->integer('likes_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}
