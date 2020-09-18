<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingWriterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_writer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_article');
            $table->unsignedBigInteger('id_user');
            $table->string('price');
            $table->unsignedBigInteger('id_payment_via');
            $table->date('date_billing');
            $table->string('proof_doc_path');
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
        Schema::dropIfExists('billing_writer');
    }
}
