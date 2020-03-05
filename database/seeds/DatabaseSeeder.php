<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(RolesTableSeeder::class);
        // $this->call(CountriesTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        $this->call(HostingProvidersTableSeeder::class);
        $this->call(DomainProvidersTableSeeder::class);
        $this->call(ExtDomainsTableSeeder::class);
        $this->call(IntDomainsTableSeeder::class);
        $this->call(BacklinksTableSeeder::class);
        $this->call(LogsTableSeeder::class);
    }
}
