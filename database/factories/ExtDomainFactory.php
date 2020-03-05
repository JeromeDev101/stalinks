<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ExtDomain;
use Faker\Generator as Faker;

$factory->define(ExtDomain::class, function (Faker $faker) {
    return [
        'domain' => $faker->name,
        'country_id' => rand(1, 3),
        'alexa_rank' => rand(0, 100),
        'ahrefs_traffic' => rand(0, 100),
        'ref_domains' => rand(0, 100),
        'linked_domains' => rand(0, 100),
        'no_backlinks' => rand(0, 100),
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'facebook' => $faker->url,
        'status' => str_random(10),
        'nopages' => rand(0, 10),
    ];
});
