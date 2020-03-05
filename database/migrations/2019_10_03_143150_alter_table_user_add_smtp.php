<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserAddSmtp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('host_work_mail')->default("smtp.gmail.com")->after('work_mail_pass');
            $table->string('port_work_mail')->default("587")->after('work_mail_pass');
            $table->string('security_work_mail')->default("tls")->after('work_mail_pass');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
