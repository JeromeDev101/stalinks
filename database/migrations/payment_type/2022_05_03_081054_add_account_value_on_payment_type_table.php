<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountValueOnPaymentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_type', function (Blueprint $table) {
            $table->string('account_value')->nullable()->after('send_payment');
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
            $table->dropColumn('account_value');
        });
    }
}
