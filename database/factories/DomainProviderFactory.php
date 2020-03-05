<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DomainProvider;
use Faker\Generator as Faker;

$factory->define(DomainProvider::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->name,
        'password' => bcrypt('123456'),
    ];
});
