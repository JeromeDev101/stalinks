<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContinentIdInPublisherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publisher', function (Blueprint $table) {
            $table->unsignedBigInteger('continent_id')->nullable()->after('country_id');

            $table->foreign('continent_id')->references('id')->on('continents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publisher', function (Blueprint $table) {
            $table->dropColumn('continent_id');
        });
    }
}
