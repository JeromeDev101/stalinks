<?php

use Illuminate\Database\Seeder;

class DomainProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\DomainProvider::class, 10)->create();
    }
}
