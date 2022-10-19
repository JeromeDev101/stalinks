<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPpaRateAndPpwRateColumnsInLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->decimal('ppa_rate', 10, 4)->after('code')->nullable()->default(0);
            $table->decimal('ppw_rate', 10, 4)->after('ppa_rate')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('ppa_rate');
            $table->dropColumn('ppw_rate');
        });
    }
}
