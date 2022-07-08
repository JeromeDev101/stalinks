<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFailReasonColumnInWriterExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('writer_exam', function (Blueprint $table) {
            $table->longText('fail_reason')->nullable()->after('attempt');
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
            $table->dropColumn('fail_reason');
        });
    }
}
