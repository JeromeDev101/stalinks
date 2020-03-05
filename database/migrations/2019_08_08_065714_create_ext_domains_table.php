<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ext_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->string('country');
            $table->integer('alexa_rank');
            $table->integer('ahrefs_traffic');
            $table->integer('ref_domains');
            $table->integer('linked_domains');
            $table->integer('no_backlinks');
            $table->string('email');
            $table->string('phone');
            $table->string('facebook');
            $table->string('status');
            $table->integer('nopages');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ext_domains');
        Schema::enableForeignKeyConstraints();
    }
}
