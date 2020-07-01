<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublisherIdOnBacklinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backlinks', function (Blueprint $table) {
            $table->unsignedBigInteger('publisher_id')->after('id');
            $table->string('selling_price')->nullable()->after('price');
            $table->string('seller_payment_status')->nullable()->after('payment_status');
            $table->string('buyer_payment_status')->nullable()->after('payment_status');
            // $table->dropForeign(['ext_domain_id', 'int_domain_id']);
            // $table->dropColumn('article_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backlinks', function (Blueprint $table) {
            $table->dropColumn('publisher_id');
            $table->dropColumn('selling_price');
            $table->dropColumn('seller_payment_status');
            $table->dropColumn('buyer_payment_status');
        });
    }
}
