<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Log::info('Seeder running');

        Author::factory()->count(5)->create();

        \Log::info('Total authors: ' . Author::count());
    }
}
