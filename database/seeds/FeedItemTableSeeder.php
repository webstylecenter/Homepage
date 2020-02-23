<?php

use App\Models\FeedItem;
use Illuminate\Database\Seeder;

class FeedItemTableSeeder extends Seeder
{
    public function run()
    {
        factory(FeedItem::class, 2500)->create();
    }
}
