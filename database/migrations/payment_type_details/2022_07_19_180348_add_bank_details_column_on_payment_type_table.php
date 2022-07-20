<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankDetailsColumnOnPaymentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_type', function (Blueprint $table) {
            $table->boolean('bank_name')->default(0);
            $table->boolean('account_name')->default(0);
            $table->boolean('account_iban')->default(0);
            $table->boolean('swift_code')->default(0);
            $table->boolean('beneficiary_add')->default(0);
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
            $table->dropColumn('bank_name');
            $table->dropColumn('account_name');
            $table->dropColumn('account_iban');
            $table->dropColumn('swift_code');
            $table->dropColumn('beneficiary_add');
        });
    }
}
