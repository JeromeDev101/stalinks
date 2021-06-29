<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusUpdatedAtColumnOnExtDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ext_domains', function (Blueprint $table) {
            $table->timestamp('status_updated_at')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ext_domains', function (Blueprint $table) {
            $table->dropColumn('status_updated_at');
        });
    }
}
