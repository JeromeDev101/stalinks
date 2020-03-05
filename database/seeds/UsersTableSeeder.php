<?php

use App\Models\User;
use App\Models\Country;
use App\Models\IntDomain;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        // factory(User::class, 10)->create()->each(function ($user) {
        //     $user->countriesAccessable()->save(factory(Country::class)->create());
        //     $user->internalDomainsAccessable()->save(factory(IntDomain::class)->create());
        // });
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@rlink.com',
                'phone' => $faker->phoneNumber,
                'role_id' => rand(1, 4),
                'avatar' => '/images/noavatar.jpg',
                'email_verified_at' => now(),
                'password' => bcrypt('123456'), // password
                'remember_token' => Str::random(10),
                'type' => 10,
            ],
//            [
//                'name' => 'Tester A',
//                'email' => 'testera@rlink.com',
//                'phone' => $faker->phoneNumber,
//                'role_id' => rand(1, 4),
//                'avatar' => '/images/noavatar.jpg',
//                'email_verified_at' => now(),
//                'password' => bcrypt('123456'), // password
//                'remember_token' => Str::random(10),
//                'type' => 0,
//            ],
//            [
//                'name' => 'Tester B',
//                'email' => 'testerB@rlink.com',
//                'phone' => $faker->phoneNumber,
//                'role_id' => rand(1, 4),
//                'avatar' => '/images/noavatar.jpg',
//                'email_verified_at' => now(),
//                'password' => bcrypt('123456'), // password
//                'remember_token' => Str::random(10),
//                'type' => 0,
//            ],
        ];

        foreach($data as $item) {
            User::firstOrCreate($item);
        }
    }
}
