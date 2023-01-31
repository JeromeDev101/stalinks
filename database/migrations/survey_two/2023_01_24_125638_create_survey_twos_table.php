<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyTwosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_twos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('one');
            $table->string('two');
            $table->string('three');
            $table->string('four');
            $table->string('five');
            $table->string('six')->nullable();
            $table->string('seven')->nullable();
            $table->string('one_other')->nullable();
            $table->string('two_other')->nullable();
            $table->string('three_other')->nullable();
            $table->string('four_other')->nullable();
            $table->string('five_other')->nullable();
            $table->string('six_other')->nullable();
            $table->string('seven_other')->nullable();
            $table->longText('comment')->nullable();
            $table->string('set', 1);
            $table->string('type')->default('buyer');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_twos');
    }
}
