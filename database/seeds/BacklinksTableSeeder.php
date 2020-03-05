<?php

use Illuminate\Database\Seeder;

class BacklinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Backlink::class, 10)->create();
    }
}
