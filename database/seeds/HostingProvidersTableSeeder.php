<?php

use Illuminate\Database\Seeder;

class HostingProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\HostingProvider::class, 10)->create();
    }
}
