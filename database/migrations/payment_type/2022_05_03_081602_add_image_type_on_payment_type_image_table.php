<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageTypeOnPaymentTypeImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_type_image', function (Blueprint $table) {
            $table->string('image_type')->default('logo')->after('payment_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_type_image', function (Blueprint $table) {
            $table->dropColumn('image_type');
        });
    }
}
