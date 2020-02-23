<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Checklist;
use Faker\Generator as Faker;

$factory->define(Checklist::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 30),
        'item' => $faker->sentence,
        'checked' => rand(0, 1)
    ];
});
