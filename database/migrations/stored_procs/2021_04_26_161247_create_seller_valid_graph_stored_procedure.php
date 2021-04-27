<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerValidGraphStoredProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure =
            'CREATE PROCEDURE `seller_valid_graph`(
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
                    SET @xaxisOrder = "ORDER BY MONTH(registration.creat1ed_at), YEAR(registration.created_at)";
                ELSE
                    SET @xaxis = "IF(registration.team_in_charge IS NOT NULL AND users.name IS NOT NULL, users.name, \'Deleted Users\') AS xaxis";
                    SET @xaxisGroup = "GROUP BY xaxis";
                    SET @xaxisOrder = "";
                END IF;
                
                IF vTeam = 0 OR vScope = \'team\' THEN
                    SET @teamFilter = "AND registration.team_in_charge IS NOT NULL";
                ELSE
                    SET @teamFilter = CONCAT("AND registration.team_in_charge = ", vTeam);
                END IF;
                SET @query = CONCAT("SELECT 
                    COUNT(IF(registration.account_validation = \'valid\', 1, NULL)) AS total_valid,
                    COUNT(registration.id) AS total_registration,
                    ", @xaxis,"
                FROM registration
                LEFT JOIN users
                ON registration.team_in_charge = users.id
                    AND users.role_id = 6
                    AND users.isOurs = 0
                WHERE registration.type = \'Seller\'
                    AND registration.created_at >= \'", vStartDate,"\'
                    AND registration.created_at <= \'", vEndDate,"\'
                    ", @teamFilter,"
                    AND registration.deleted_at IS NULL
                ", @xaxisGroup, @xaxisOrder);
            
            PREPARE stmt FROM @query;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
            END';

        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS seller_valid_graph');
        \Illuminate\Support\Facades\DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS seller_valid_graph');
    }
}
