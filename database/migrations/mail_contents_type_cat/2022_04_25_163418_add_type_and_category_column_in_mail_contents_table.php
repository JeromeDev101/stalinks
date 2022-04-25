<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndCategoryColumnInMailContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_contents', function (Blueprint $table) {
            $table->string('category')->nullable()->after('country_id');
            $table->string('type')->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_contents', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('type');
        });
    }
}
