<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::all();
        $publishers = Publisher::all();

        for($i = 0; $i < 20; $i++){
            Book::create([
                'title' => fake()->sentence(3),
                'author_id'=> $authors->random()->id,
                'publisher_id'=>$publishers->random()->id,
                'published_date'=> fake()->date()
            ]);
        }
    }
}
