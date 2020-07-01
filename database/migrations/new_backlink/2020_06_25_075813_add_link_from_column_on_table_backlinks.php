<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkFromColumnOnTableBacklinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backlinks', function (Blueprint $table) {
            $table->string('link_from')->nullable()->after('link');
            $table->string('url_from')->nullable()->after('url_advertiser');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backlinks', function (Blueprint $table) {
            $table->dropColumn('link_from');
            $table->dropColumn('url_from');
        });
    }
}
