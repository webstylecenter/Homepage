<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\FeedItem;
use Faker\Generator as Faker;

$factory->define(FeedItem::class, function (Faker $faker) {
    return [
        'feed_id' => rand(1, 50),
        'guid' => $faker->md5($faker->url),
        'title' => $faker->sentence,
        'description' => $faker->realText(255),
        'url' => $faker->url,
        'pinned' => (rand(1, 100) > 80)
    ];
});
