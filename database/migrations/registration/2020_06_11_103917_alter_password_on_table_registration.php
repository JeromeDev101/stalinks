<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPasswordOnTableRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registration', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('skype')->nullable()->change();
            $table->string('id_payment_type')->nullable()->change();
            $table->string('commission')->nullable()->change();
            $table->string('company_name')->nullable()->change();
            $table->string('verification_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
