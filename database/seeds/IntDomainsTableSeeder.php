<?php

use Illuminate\Database\Seeder;

class IntDomainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\IntDomain::class, 10)->create();
    }
}
