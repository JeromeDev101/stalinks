<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndecesToPublisherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publisher', function (Blueprint $table) {
            $table->index([
                'user_id',
                'country_id',
                'continent_id',
                'language_id',
                'valid',
                'qc_validation',
                'href_fetched_at',
                'deleted_at'
            ], 'publisher_list_backlinks_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publisher', function (Blueprint $table) {
            $table->dropIndex([
                'user_id',
                'country_id',
                'continent_id',
                'language_id',
                'valid',
                'qc_validation',
                'href_fetched_at',
                'deleted_at'
            ]);
        });
    }
}
