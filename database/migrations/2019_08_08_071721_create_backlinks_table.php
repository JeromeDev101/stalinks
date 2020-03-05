<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBacklinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backlinks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ext_domain_id');
            $table->unsignedBigInteger('int_domain_id');
            $table->string('link');
            $table->decimal('price', 18,4)->default(0);
            $table->string('anchor_text');
            $table->date('live_date');
            $table->string('status');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ext_domain_id')->references('id')->on('ext_domains');
            $table->foreign('int_domain_id')->references('id')->on('int_domains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('backlinks');
        Schema::enableForeignKeyConstraints();
    }
}
