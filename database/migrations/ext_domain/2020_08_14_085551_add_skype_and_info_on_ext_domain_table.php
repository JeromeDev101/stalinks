<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkypeAndInfoOnExtDomainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ext_domains', function (Blueprint $table) {
            $table->longText('skype')->nullable()->after('facebook');
            $table->longText('info')->nullable()->after('skype');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ext_domains', function (Blueprint $table) {
            //
        });
    }
}
