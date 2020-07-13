<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_backlink');
            $table->unsignedBigInteger('id_writer');
            $table->unsignedBigInteger('id_language');
            $table->date('date_start');
            $table->date('date_complete');
            $table->longText('content');
            $table->unsignedBigInteger('id_writer_price');
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
        Schema::dropIfExists('article');
    }
}
