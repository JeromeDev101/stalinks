<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCodeCombStoredProc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "CREATE PROCEDURE `compute_price_sp`()
            BEGIN
                DECLARE bDone int;
            
                DECLARE vId BIGINT(20);
                DECLARE vUr VARCHAR(255);
                DECLARE vDr VARCHAR(255);
                DECLARE vUrDrScore INT;
                DECLARE vBlRdScore INT;
                DECLARE vBacklinks VARCHAR(255);
                DECLARE vRef_Domain VARCHAR(255);
                DECLARE vOrg_keywords VARCHAR(255);
                DECLARE vOrg_traffic VARCHAR(255);
                DECLARE vPrice varchar(255);
                DECLARE vCodePrice INT;
                DECLARE codeCombiURDR CHAR(1);
                DECLARE codeCombiBLRD CHAR(1);
                DECLARE codeCombiOrgKw CHAR(1);
                DECLARE codeCombiOrgTraffic CHAR(1);
                DECLARE codeCombination CHAR(5);
                DECLARE vResult1 float;
                DECLARE vResult2 float;
                DECLARE vPriceBasis VARCHAR(7);
                DECLARE vCounter int;
            
                DECLARE cPublisher CURSOR FOR SELECT id, ur, dr, backlinks, ref_domain, org_keywords, org_traffic, price FROM publisher WHERE deleted_at IS NULL;
                
                OPEN cPublisher;
                
                SET bDone = 0;
                SET vCounter = 0;
                
                REPEAT
                    FETCH cPublisher INTO vId, vUr, vDr, vBacklinks, vRef_domain, vOrg_keywords, vOrg_traffic, vPrice;
                    SET vCounter = vCounter + 1;
                    
                    SET vUrDrScore = vDr - vUr;


                    IF ( vUr >= 0 AND vUr <= 9 ) OR  ( vDr >= 0 AND vDr <= 9 ) THEN
                        SET codeCombiURDR = 'A';
                    ELSEIF ( vUr >= 10 AND vUr <= 100 ) AND  ( vDr >= 10 AND vDr <= 19 ) THEN
                        IF vUrDrScore >= -9 AND vUrDrScore <= 9 THEN
                            SET codeCombiURDR = 'D';
                        ELSE
                            SET codeCombiURDR = 'E';
                        END IF;
                    ELSEIF ( vUr >= 10 AND vUr <= 19 ) AND  ( vDr >= 20 AND vDr <= 100 ) THEN
                        IF vUrDrScore >= 1 AND vUrDrScore <= 15 THEN
                            SET codeCombiURDR = 'D';
                        ELSE
                            SET codeCombiURDR = 'E';
                        END IF;
                    ELSEIF ( vUr >= 20 AND vUr <= 100 ) AND  ( vDr >= 20 AND vDr <= 34 ) THEN
                        IF vUrDrScore >= -15 AND vUrDrScore <= -1 THEN
                            SET codeCombiURDR = 'D';
                        ELSEIF vUrDrScore <= -16 THEN
                            SET codeCombiURDR = 'E';
                        ELSE
                            SET codeCombiURDR = 'C';
                        END IF;
                    ELSEIF ( vUr >= 20 AND vUr <= 34 ) AND  ( vDr >= 35 AND vDr <= 100 ) THEN
                        IF vUrDrScore >= 1 AND vUrDrScore <= 16 THEN
                            SET codeCombiURDR = 'B';
                        ELSE
                            SET codeCombiURDR = 'C';
                        END IF;
                    ELSEIF ( vUr >= 35 AND vUr <= 49 ) AND  ( vDr >= 35 AND vDr <= 49 ) THEN
                        IF vUrDrScore >= -9 AND vUrDrScore <= 9 THEN
                            SET codeCombiURDR = 'B';
                        ELSE
                            SET codeCombiURDR = 'C';
                        END IF;
                    ELSEIF ( vUr >= 50 AND vUr <= 100 ) AND  ( vDr >= 35 AND vDr <= 49 ) THEN
                        IF vUrDrScore >= -15 AND vUrDrScore <= -5 THEN
                            SET codeCombiURDR = 'D';
                        ELSEIF vUrDrScore <= -16 THEN
                            SET codeCombiURDR = 'E';
                        ELSE
                            SET codeCombiURDR = 'A';
                        END IF;
                    ELSEIF ( vUr >= 35 AND vUr <= 49 ) AND  ( vDr >= 50 AND vDr <= 100 ) THEN
                        IF vUrDrScore >= 1 AND vUrDrScore <= 5 THEN
                            SET codeCombiURDR = 'A';
                        ELSE
                            SET codeCombiURDR = 'B';
                        END IF;
                    ELSEIF ( vUr >= 50 AND vUr <= 100 ) AND  ( vDr >= 50 AND vDr <= 100 ) THEN
                        IF vUrDrScore >= -5 AND vUrDrScore <= 15 THEN
                            SET codeCombiURDR = 'A';
                        ELSE
                            SET codeCombiURDR = 'B';
                        END IF;
                    ELSE 
                        SET codeCombiURDR = '';
                    END IF;


                    
                    IF vRef_domain = 0 THEN
                        SET vBlRdScore = 0;
                    ELSE
                        SET vBlRdScore = vBacklinks / vRef_domain;
                    END IF;
                    
                    IF vBacklinks = 0 THEN
                        SET codeCombiBLRD = '';
                    ELSEIF vBlRdScore >= 1 AND vBlRdScore < 3 THEN
                        SET codeCombiBLRD = 'A';
                    ELSEIF vBlRdScore >= 3 AND vBlRdScore < 8 THEN
                        SET codeCombiBLRD = 'C';
                    ELSEIF vBlRdScore >= 8 AND vBlRdScore < 16 THEN
                        SET codeCombiBLRD = 'D';
                    ELSEIF vBlRdScore >= 16 THEN
                        SET codeCombiBLRD = 'E';
                    END IF;
                    
                    IF vOrg_keywords >= 1000 THEN
                        SET codeCombiOrgKw = 'A';
                    ELSEIF vOrg_keywords >= 500 AND vOrg_keywords < 1000 THEN
                        SET codeCombiOrgKw = 'B';
                    ELSEIF vOrg_keywords >= 100 AND vOrg_keywords < 500 THEN
                        SET codeCombiOrgKw = 'C';
                    ELSEIF vOrg_keywords>= 50 AND vOrg_keywords < 100 THEN
                        SET codeCombiOrgKw = 'D';
                    ELSEIF vOrg_keywords < 50 THEN
                        SET codeCombiOrgKw = 'E';
                    END IF;
                    
                    IF vOrg_traffic >= 10000 THEN
                        SET codeCombiOrgTraffic = 'A';
                    ELSEIF vOrg_traffic >= 5000 AND vOrg_traffic < 10000 THEN
                        SET codeCombiOrgTraffic = 'B';
                    ELSEIF vOrg_traffic >= 1000 AND vOrg_traffic < 5000 THEN
                        SET codeCombiOrgTraffic = 'C';
                    ELSEIF vOrg_traffic>= 500 AND vOrg_traffic < 1000 THEN
                        SET codeCombiOrgTraffic = 'D';
                    ELSEIF vOrg_traffic < 500 THEN
                        SET codeCombiOrgTraffic = 'E';
                    END IF;
                    
                    SET codeCombination = CONCAT(codeCombiURDR, codeCombiBLRD, codeCombiOrgKw, codeCombiOrgTraffic);
                    
                    SELECT price INTO vCodePrice FROM price_list WHERE code LIKE codeCombination;
                    
                    IF vPrice = '' THEN
						SET vPrice = 0;
					END IF;
                    
                    SET vResult1 = vCodePrice * 0.7;
                    SET vResult2 = (vCodePrice * 0.1) + vCodePrice;
                    
                    IF vPrice <= vResult1 THEN
                        SET vPriceBasis = 'Good';
                    ELSEIF vPrice > vResult1 AND vResult1 < vResult2 THEN
                        SET vPriceBasis = 'Average';
                    ELSEIF vPrice > vResult2 THEN
                        SET vPriceBasis = 'High';
                    ELSE 
                        SET vPriceBasis = 'High';
                    END IF;
                    
                    UPDATE publisher SET code_comb = codeCombination, code_price = vCodePrice, price_basis = vPriceBasis WHERE id = vId; 
                    
                UNTIL bDone = 1 END REPEAT;
                close cPublisher;
                
                select vCounter;
            END";

        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS compute_price_sp');
        \Illuminate\Support\Facades\DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::unprepared('DROP procedure IF EXISTS compute_price_sp');
    }
}
