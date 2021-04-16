<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sampel;
use Faker\Generator as Faker;

$factory->define(Sampel::class, function (Faker $faker) {
    return [
        'nomor_sampel' => $faker->randomNumber(),
        'sampel_status' => 'waiting_sample',
    ];
});
