<?php

use App\Models\Checklist;
use Illuminate\Database\Seeder;

class ChecklistTableSeeder extends Seeder
{
    public function run()
    {
        factory(Checklist::class, 230)->create();
    }
}
