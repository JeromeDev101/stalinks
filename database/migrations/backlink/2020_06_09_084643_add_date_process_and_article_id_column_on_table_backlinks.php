<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateProcessAndArticleIdColumnOnTableBacklinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backlinks', function (Blueprint $table) {
            $table->date('date_process')->after('user_id');
            $table->unsignedBigInteger('article_id')->after('date_process');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backlinks', function (Blueprint $table) {
            $table->dropColumn('date_process');
            $table->dropColumn('article_id');
        });
    }
}
