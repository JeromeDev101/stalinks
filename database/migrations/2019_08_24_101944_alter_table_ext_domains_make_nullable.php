<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableExtDomainsMakeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ext_domains', function (Blueprint $table) {
            $table->integer('alexa_rank')->default(0)->change();
            $table->integer('ahrefs_traffic')->default(0)->change();
            $table->integer('ref_domains')->default(0)->change();
            $table->integer('linked_domains')->default(0)->change();
            $table->integer('no_backlinks')->default(0)->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('facebook')->nullable()->change();
            $table->string('status')->default(config('constant.EXT_STATUS_NEW'))->change();
            $table->integer('nopages')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
