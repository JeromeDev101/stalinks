<?php

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('configs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            // Alexa
            [
                'id' => 1,
                'name' => 'API Password',
                'code' => 'api_password',
                'value' => 'QHF8FpvasWdFLWV',
                'type' => 'alexa'
            ],
            [
                'id' => 2,
                'name' => 'API Key',
                'code' => 'api_key',
                'value' => 'bzh0HToFzE8joqIRDsdCk8c30B1w09Kj9nQBzFS4',
                'type' => 'alexa'
            ],
            [
                'id' => 3,
                'name' => 'API User',
                'code' => 'api_user',
                'value' => 'hanvdt@gmail.com',
                'type' => 'alexa'
            ],

            //Ahrefs
            [
                'id' => 4,
                'name' => 'Token',
                'code' => 'token',
                'value' => '929b05bb1165cddd3fe562f240cedc83',
                'type' => 'ahrefs'
            ],

            [
                'id' => 5,
                'name' => 'Base URL',
                'code' => 'base_url',
                'value' => 'http://apiv2.dexuat.com/?mode=ahrefs.com&target=',
                'type' => 'ahrefs'
            ],

            [
                'id' => 6,
                'name' => 'E-Mail Name',
                'code' => 'email_name',
                'value' => 'Rlink System',
                'type' => 'email'
            ],
        ];

        foreach($data as $item) {
            Config::firstOrCreate($item);
        }
    }
}
