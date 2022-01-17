<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAffiliateCodeAndIdColumnInRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registration', function (Blueprint $table) {
            $table->string('affiliate_code', 100)->nullable()->after('team_in_charge');
            $table->unsignedBigInteger('affiliate_id')->nullable()->after('affiliate_code');

            $table->foreign('affiliate_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registration', function (Blueprint $table) {
            $table->dropColumn('affiliate_code');
            $table->dropColumn('affiliate_id');
        });
    }
}
