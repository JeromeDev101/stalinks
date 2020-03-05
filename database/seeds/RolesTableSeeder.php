<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
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
                'name' => 'Robo Admin',
                'description' => 'Robo Admin'
            ],

            [
                'name' => 'Robo Partner',
                'description' => 'Robo Partner'
            ],

            [
                'name' => 'Robo Dev',
                'description' => 'Robo Dev'
            ],
            [
                'name' => 'Robo Guest Posting',
                'description' => 'Robo Guest Posting'
            ],
        ];

        foreach($data as $item) {
            Role::firstOrCreate($item);
        }
    }
}
