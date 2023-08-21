<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkInjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_injections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('publisher_id');
            $table->unsignedBigInteger('buyer_id');
            $table->string('price');
            $table->string('prices');
            $table->string('url_article')->nullable();
            $table->string('url_advertiser')->nullable();
            $table->string('link')->nullable();
            $table->string('anchor_text')->nullable();
            $table->string('status');
            $table->string('payment_status')->nullable();
            $table->date('date_process');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_injections');
    }
}
