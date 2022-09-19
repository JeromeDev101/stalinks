<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyerTypeOnFormulaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula', function (Blueprint $table) {
            $table->double('basic')->after('percentage')->default(0);
            $table->double('medium')->after('basic')->default(0);
            $table->double('premium')->after('medium')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formula', function (Blueprint $table) {
            $table->dropColumn('basic');
            $table->dropColumn('medium');
            $table->dropColumn('premium');
        });
    }
}
