<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Publisher;

class PriceBasisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $publisher = Publisher::select('id','price', 'code_price')->whereNull('deleted_at')->get();

        foreach($publisher as $key => $value) {

            $result_1 = 0;
            $result_2 = 0;

            $price_basis = '-';
            if( !empty($value->code_price) ){

                $var_a = floatVal($value->price);
                $var_b = floatVal($value->code_price);

                $result_1 = number_format($var_b * 0.7,2);
                $result_2 = number_format( ($var_b * 0.1) + $var_b, 2);

                if( $result_1 != 0 && $result_2 != 0 ){
                    if( $var_a <= $result_1 ){
                        $price_basis = 'Good';
                    }

                    if( $var_a > $result_1 && $result_1 < $result_2 ){
                        $price_basis = 'Average';
                    }

                    if( $var_a > $result_2 ){
                        $price_basis = 'High';
                    }
                }
            }

            if( $value->code_price == 0 && $value->price > 0){
                $price_basis = 'High';
            }

            Publisher::find($value->id)->update([
                'price_basis' => $price_basis
            ]);
        }
    }
}
