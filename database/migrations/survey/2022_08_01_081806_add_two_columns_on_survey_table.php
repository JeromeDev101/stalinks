<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoColumnsOnSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey', function (Blueprint $table) {
            $table->string('seven')->after('six')->nullable();
            $table->string('seven_other')->after('six_other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey', function (Blueprint $table) {
            $table->dropColumn('seven');
            $table->dropColumn('seven_other');
        });
    }
}
