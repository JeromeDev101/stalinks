<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->date('registered_at')->nullable()->after('details');
            $table->date('expired_at')->nullable()->after('registered_at');
            $table->boolean('is_expired')->nullable()->default(0)->after('expired_at');
            $table->tinyInteger('is_notified')->nullable()->after('is_expired');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('registered_at');
            $table->dropColumn('expired_at');
            $table->dropColumn('is_expired');
            $table->dropColumn('is_notified');
        });
    }
}
