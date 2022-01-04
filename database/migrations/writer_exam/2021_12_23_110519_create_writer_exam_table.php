<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWriterExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer_exam', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('writer_id');
            $table->text('anchor_text');
            $table->text('link_to');
            $table->text('title')->nullable();
            $table->integer('num_words')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('status');
            $table->date('deleted_at')->nullable();
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
        Schema::dropIfExists('writer_exam');
    }
}
