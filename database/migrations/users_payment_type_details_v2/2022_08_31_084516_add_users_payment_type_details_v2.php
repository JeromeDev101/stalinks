<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersPaymentTypeDetailsV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_payment_type', function (Blueprint $table) {
            $table->longText('account_holder')->nullable();
            $table->longText('account_type')->nullable();
            $table->longText('routing_num')->nullable();
            $table->longText('wire_routing_num')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_payment_type', function (Blueprint $table) {
            $table->dropColumn('account_holder');
            $table->dropColumn('account_type');
            $table->dropColumn('routing_num');
            $table->dropColumn('wire_routing_num');
        });
    }
}
