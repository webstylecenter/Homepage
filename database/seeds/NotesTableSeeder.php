<?php

use App\Models\Note;
use Illuminate\Database\Seeder;

class NotesTableSeeder extends Seeder
{
    public function run()
    {
        factory(Note::class, 100)->create();
    }
}
