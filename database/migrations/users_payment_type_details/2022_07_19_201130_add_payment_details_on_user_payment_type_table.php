<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentDetailsOnUserPaymentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_payment_type', function (Blueprint $table) {
            $table->longText('bank_name')->nullable();
            $table->longText('account_name')->nullable();
            $table->longText('account_iban')->nullable();
            $table->longText('swift_code')->nullable();
            $table->longText('beneficiary_add')->nullable();
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
            $table->dropColumn('bank_name');
            $table->dropColumn('account_name');
            $table->dropColumn('account_iban');
            $table->dropColumn('swift_code');
            $table->dropColumn('beneficiary_add');
        });
    }
}
