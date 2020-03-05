<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorExtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ext_domains', function (Blueprint $table) {
            $table->dropColumn('ahrefs_traffic');
            $table->dropColumn('linked_domains');
            $table->dropColumn('no_backlinks');
            $table->dropColumn('nopages');

            $table->unsignedBigInteger('organic_traffic')->after('country_id')->default(0);
            $table->unsignedBigInteger('organic_keywords')->after('country_id')->default(0);
            $table->unsignedBigInteger('domain_rating')->after('country_id')->default(0);
            $table->unsignedBigInteger('url_rating')->after('country_id')->default(0);
            $table->unsignedBigInteger('backlinks')->after('country_id')->default(0);
            $table->unsignedBigInteger('ahrefs_rank')->after('country_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
