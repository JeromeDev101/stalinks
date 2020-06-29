<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_backlink');
            $table->unsignedBigInteger('id_user');
            $table->string('seller_price');
            $table->unsignedBigInteger('id_payment_via');
            $table->date('date_billing');
            $table->string('proof_doc_path');
            $table->boolean('admin_confirmation');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_backlink')->references('id')->on('backlinks');
            $table->foreign('id_payment_via')->references('id')->on('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing');
    }
}
