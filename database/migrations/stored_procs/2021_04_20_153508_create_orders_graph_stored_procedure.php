<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersGraphStoredProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure =
        'CREATE PROCEDURE `orders_graph`(
            vTeamInCharge int,
            vStartDate varchar(10),
            vEndDate varchar(10)
        )
        BEGIN
            IF vTeamInCharge != 0 THEN
		        SET @qTeamInCharge = CONCAT(\'AND registration.team_in_charge =\', vTeamInCharge);
            ELSE
                SET @qTeamInCharge = \'\';
            END IF;
            
            SET @query = CONCAT("SELECT 
				MONTH(MAX(date_process)) AS month,
				year(MAX(date_process)) AS year,
				count(IF(backlinks.status IN (\'Processing\', \'Content In Writing\', \'Content Done\', \'Content Sent\'), 1, NULL)) AS processing,
				count(IF(backlinks.status = \'Live\', 1, NULL)) AS live, 
				count(IF(backlinks.status = \'Canceled\', 1, NULL)) AS cancelled,
				count(IF(backlinks.status = \'Issue\', 1, NULL)) AS issue,
				count(IF(backlinks.status = \'Live In Process\', 1, NULL)) AS live_in_process,
				count(IF(backlinks.status IS NOT NULL, 1, NULL)) AS orders,
				CONCAT(MONTHNAME(MAX(date_process)), \" \", year(MAX(date_process))) AS xaxis
			FROM backlinks
			LEFT JOIN publisher
			ON publisher.id = backlinks.publisher_id
			INNER JOIN users
			ON users.id = publisher.user_id
			INNER JOIN registration
			ON registration.email = users.email
				WHERE backlinks.deleted_at IS NULL
                AND backlinks.date_process >= \'", vStartDate,"\'
                AND backlinks.date_process <= \'", vEndDate,"\'
				", @qTeamInCharge,"
			GROUP BY MONTH(date_process)
			ORDER BY year, month;");
            
            PREPARE stmt FROM @query;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        END';

        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS orders_graph');
        \Illuminate\Support\Facades\DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS orders_graph');
    }
}
