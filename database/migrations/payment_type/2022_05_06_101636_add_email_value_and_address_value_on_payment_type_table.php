<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailValueAndAddressValueOnPaymentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_type', function (Blueprint $table) {
            $table->string('email_value')->after('account_value')->nullable();
            $table->string('address_value')->after('email_value')->nullable();
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
            $table->dropColumn('email_value');
            $table->dropColumn('address_value');
        });
    }
}
