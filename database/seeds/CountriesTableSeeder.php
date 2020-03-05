<?php

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Việt Nam',
                'code' => 'VN'
            ],

            [
                'name' => 'Trung Quốc',
                'code' => 'TQ'
            ],

            [
                'name' => 'Mỹ',
                'code' => 'USA'
            ],
            [
                'name' => 'NGA',
                'code' => 'RS'
            ],
        ];

        foreach($data as $item) {
            Country::firstOrCreate($item);
        }
    }
}
