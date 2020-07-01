<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublisherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publisher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('url');
            $table->string('ur')->nullable()->default(0);
            $table->string('dr')->nullable()->default(0);
            $table->string('backlinks')->nullable()->default(0);
            $table->string('ref_domain')->nullable()->default(0);
            $table->string('org_keywords')->nullable()->default(0);
            $table->string('org_traffic')->nullable()->default(0);
            $table->string('price')->nullable();
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
        Schema::dropIfExists('publisher');
    }
}
