<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ExtDomain;
use App\Models\User;
use App\Models\Backlink;
use App\Models\IntDomain;
use Faker\Generator as Faker;

$factory->define(Backlink::class, function (Faker $faker) {
    return [
        'ext_domain_id' => factory(ExtDomain::class)->create()->id,
        'int_domain_id' => factory(IntDomain::class)->create()->id,
        'link' => $faker->url,
        'price' => $faker->randomNumber(2),
        'anchor_text' => str_random(),
        'live_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'status' => str_random(),
        'user_id' => factory(User::class)->create()->id,
    ];
});
