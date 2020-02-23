<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Note;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 30),
        'name' => $faker->jobTitle,
        'content' => $faker->realText(rand(100, 20000)),
        'position' => rand(1, 10)
    ];
});
