<?php

use App\Models\Feed;
use Illuminate\Database\Seeder;

class FeedTableSeeder extends Seeder
{
    public function run()
    {
        factory(Feed::class, 50)->create();
    }
}
