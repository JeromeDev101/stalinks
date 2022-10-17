<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWriterValidGraphStoredProcTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure =
            'CREATE PROCEDURE `writer_valid_graph`(
                vStartDate varchar(10),
                vEndDate varchar(10),
                vScope varchar(10),
                vTeam int
            )
            BEGIN
                IF vScope = \'global\' THEN
                    SET @xaxis = "CONCAT(MONTHNAME(MAX(registration.created_at)), \' \', YEAR(MAX(registration.created_at))) AS xaxis,
                    MONTHNAME(MAX(registration.created_at)) AS month,
                    YEAR(MAX(registration.created_at)) AS year";
                    SET @xaxisGroup = "GROUP BY MONTH(registration.created_at), YEAR(registration.created_at)";
                    SET @xaxisOrder = "ORDER BY YEAR(registration.created_at), MONTH(registration.created_at)";
                ELSE
                    SET @xaxis = "IF(registration.team_in_charge IS NOT NULL AND users.name IS NOT NULL, users.name, \'Deleted Users\') AS xaxis";
                    SET @xaxisGroup = "GROUP BY xaxis";
                    SET @xaxisOrder = "ORDER BY xaxis";
                END IF;

                IF vTeam = 0 OR vScope = \'team\' THEN
                    SET @teamFilter = "AND registration.team_in_charge IS NOT NULL";
                ELSE
                    SET @teamFilter = CONCAT("AND registration.team_in_charge = ", vTeam);
                END IF;
                SET @query = CONCAT("SELECT
                    COUNT(IF(registration.account_validation = \'valid\', 1, NULL)) AS total_valid,
                    COUNT(registration.id) AS total_registration,
                    COUNT(IF(registration.account_validation = \'valid\' AND pub.id IS NOT NULL, 1, NULL)) AS valid_with_url,
                    ", @xaxis,"
                FROM registration
                LEFT JOIN users
                ON registration.team_in_charge = users.id
                    AND users.role_id = 4
                    AND users.isOurs = 1
                LEFT JOIN (
                        SELECT id,id_writer
                        FROM article
                        GROUP BY id_writer, id
                    ) AS pub ON users.id = pub.id_writer
                WHERE registration.type = \'Writer\'
                    AND DATE(registration.created_at) >= \'", vStartDate,"\'
                    AND DATE(registration.created_at) <= \'", vEndDate,"\'
                    ", @teamFilter,"
                    AND registration.deleted_at IS NULL
                ", @xaxisGroup, " ", @xaxisOrder);

                PREPARE stmt FROM @query;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            END';

        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS writer_valid_graph');
        \Illuminate\Support\Facades\DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS writer_valid_graph');
    }
}
