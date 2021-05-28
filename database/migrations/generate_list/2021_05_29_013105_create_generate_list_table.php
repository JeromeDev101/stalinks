<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenerateListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generate_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->string('ur')->nullable();
            $table->string('dr')->nullable();
            $table->string('code_1')->nullable();
            $table->string('backlinks')->nullable();
            $table->string('ref_domain')->nullable();
            $table->string('code_2')->nullable();
            $table->string('org_kw')->nullable();
            $table->string('code_3')->nullable();
            $table->string('org_traffic')->nullable();
            $table->string('code_4')->nullable();
            $table->string('code_comb')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generate_list');
    }
}
