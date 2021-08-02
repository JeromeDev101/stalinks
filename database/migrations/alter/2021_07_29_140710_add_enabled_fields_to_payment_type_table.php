<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnabledFieldsToPaymentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_type', function (Blueprint $table) {
            $table->string('send_payment', '3')->after('type')->default('yes');
            $table->string('receive_payment', '3')->after('type')->default('yes');
            $table->string('show_registration', '3')->after('type')->default('yes');
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
            $table->dropColumn('send_payment');
            $table->dropColumn('receive_payment');
            $table->dropColumn('show_registration');
        });
    }
}
