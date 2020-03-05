<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\HostingProvider;
use Faker\Generator as Faker;

$factory->define(HostingProvider::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->name,
        'link' => $faker->url,
        'password' => bcrypt('123456'),
    ];
});
