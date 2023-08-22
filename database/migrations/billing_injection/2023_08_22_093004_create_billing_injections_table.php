<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingInjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_injections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_injection');
            $table->integer('id_user');
            $table->string('buyer_price');
            $table->string('seller_price');
            $table->string('commission');
            $table->integer('id_payment_via');
            $table->longText('proof_doc_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_injections');
    }
}
