<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\IntDomain;
use App\Models\DomainProvider;
use App\Models\HostingProvider;
use Faker\Generator as Faker;

$factory->define(IntDomain::class, function (Faker $faker) {
    return [
        'domain' => $faker->name,
        'domain_provider_id' => factory(DomainProvider::class)->create()->id,
        'hosting_provider_id' => factory(HostingProvider::class)->create()->id,
        'user_id' => factory(User::class)->create()->id,
        'country_id' => rand(1, 3),
    ];
});
