<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLinkInjectionTableMakeInjectionPriceAndBuyerInjectionPriceNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_injections', function (Blueprint $table) {
            $table->string('injection_price')->nullable()->change();
            $table->string('buyer_injection_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_injections', function (Blueprint $table) {
            //
        });
    }
}
