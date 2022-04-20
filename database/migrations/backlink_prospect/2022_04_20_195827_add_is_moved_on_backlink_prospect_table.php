<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMovedOnBacklinkProspectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backlink_prospect', function (Blueprint $table) {
            $table->boolean('is_moved')->default(0)->after('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backlink_prospect', function (Blueprint $table) {
            $table->dropColumn('is_moved');
        });
    }
}
