<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArticlesTableAddReminderColumnsAndDateStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dateTime('date_start')->nullable()->change();
            $table->string('reminded_via', 100)->nullable()->after('id_writer_price');;
            $table->timestamp('reminded_at')->nullable()->after('reminded_via');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dropColumn('reminded_via');
            $table->dropColumn('reminded_at');
        });
    }
}
