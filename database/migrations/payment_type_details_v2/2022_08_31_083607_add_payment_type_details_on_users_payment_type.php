<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTypeDetailsOnUsersPaymentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_type', function (Blueprint $table) {
            $table->boolean('account_holder')->default(0);
            $table->boolean('account_type')->default(0);
            $table->boolean('routing_num')->default(0);
            $table->boolean('wire_routing_num')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_type', function (Blueprint $table) {
            $table->dropColumn('account_holder');
            $table->dropColumn('account_type');
            $table->dropColumn('routing_num');
            $table->dropColumn('wire_routing_num');
        });
    }
}
