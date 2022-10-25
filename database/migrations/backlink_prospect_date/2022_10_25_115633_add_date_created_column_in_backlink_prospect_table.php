<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateCreatedColumnInBacklinkProspectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backlink_prospect', function (Blueprint $table) {
            $table->timestamp('date_created')->nullable()->after('is_moved');
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
            $table->dropColumn('date_created');
        });
    }
}
