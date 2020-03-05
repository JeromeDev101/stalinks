<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('int_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('domain_provider_id');
            $table->unsignedBigInteger('hosting_provider_id');
            $table->unsignedBigInteger('country_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('domain_provider_id')->references('id')->on('domain_providers');
            $table->foreign('hosting_provider_id')->references('id')->on('hosting_providers');
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
        Schema::dropIfExists('int_domains');
        Schema::enableForeignKeyConstraints();
    }
}
