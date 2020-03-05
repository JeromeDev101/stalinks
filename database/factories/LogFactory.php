<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Log;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Log::class, function (Faker $faker) {
    return [
        'action' => $faker->paragraph,
        'user_id' => factory(User::class)->create()->id,
    ];
});
