<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsGeneralTemplateColumnOnMailContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_contents', function (Blueprint $table) {
            $table->boolean('is_general_template')->default(0)->after('country_id');
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
            $table->dropColumn('is_general_template');
        });
    }
}