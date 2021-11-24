<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublisherIdUserIdBuyerIndexToBuyerPurchasedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_purchased', function (Blueprint $table) {
            $table->index([
                'publisher_id',
                'user_id_buyer'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_purchased', function (Blueprint $table) {
            $table->dropIndex([
                'publisher_id',
                'user_id_buyer'
            ]);
        });
    }
}
