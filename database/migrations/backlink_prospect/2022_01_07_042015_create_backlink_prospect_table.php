<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBacklinkProspectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backlink_prospect', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('referring_domain');
            $table->string('ur')->nullable();
            $table->string('dr')->nullable();
            $table->string('backlinks')->nullable();
            $table->string('org_kw')->nullable();
            $table->string('org_traffic')->nullable();
            $table->string('ref_domain_ahref')->nullable();
            $table->string('category')->nullable();
            $table->string('status')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('backlink_prospect');
    }
}
