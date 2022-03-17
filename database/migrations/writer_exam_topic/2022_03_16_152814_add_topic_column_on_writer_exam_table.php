<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTopicColumnOnWriterExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('writer_exam', function (Blueprint $table) {
            $table->text('topic')->nullable()->after('anchor_text');
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
            $table->dropColumn('topic');
        });
    }
}
