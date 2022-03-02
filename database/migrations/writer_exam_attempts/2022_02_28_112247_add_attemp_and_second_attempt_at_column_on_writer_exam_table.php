<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttempAndSecondAttemptAtColumnOnWriterExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('writer_exam', function (Blueprint $table) {
            $table->smallInteger('attempt')->after('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('writer_exam', function (Blueprint $table) {
            $table->dropColumn('attempt');
        });
    }
}
