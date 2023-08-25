<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBacklinksTableAddIdeaForTitleColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backlinks', function (Blueprint $table) {
            $table->string('idea_for_title')->nullable()->after('link_from');
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
            $table->dropColumn('idea_for_title');
        });
    }
}
