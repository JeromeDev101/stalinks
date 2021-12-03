<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndecesToRepliesTableTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->index([
                'from_mail',
                'status_code',
                'is_sent',
                'label_id',
            ], 'replies_mails_index_two');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropIndex([
                'from_mail',
                'status_code',
                'is_sent',
                'label_id',
            ]);
        });
    }
}
