<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLinkInjectionsTableAddReasonColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_injections', function (Blueprint $table) {
            $table->longText('reason')->after('status')->nullable();
            $table->longText('reason_detailed')->after('reason')->nullable();
            $table->string('reason_file')->nullable()->after('reason_detailed');
            $table->date('date_requested')->nullable()->after('date_process');
            $table->date('live_date')->nullable()->after('date_requested');
            $table->date('date_process')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_injections', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->dropColumn('reason_detailed');
            $table->dropColumn('reason_file');
            $table->dropColumn('date_requested');
            $table->dropColumn('live_date');
        });
    }
}
