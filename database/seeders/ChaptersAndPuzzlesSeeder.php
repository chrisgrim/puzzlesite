<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChaptersAndPuzzlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define chapters data
        $chapters = [
            ['title' => 'Chapter 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Chapter 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        // Insert chapters into the database and get their IDs
        DB::table('chapters')->insert($chapters);
        $chapterIds = DB::table('chapters')->pluck('id');

        // Define puzzles data for each chapter
        $puzzles = [];
        foreach ($chapterIds as $chapterId) {
            for ($i = 1; $i <= 5; $i++) {
                $puzzles[] = [
                    'chapter_id' => $chapterId,
                    'title' => "Puzzle $i for Chapter $chapterId",
                    'description' => "Description for Puzzle $i of Chapter $chapterId",
                    'solution' => "HELLO",
                    'difficulty' => rand(1, 5), // Random difficulty between 1 and 5
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // Insert puzzles into the database
        DB::table('puzzles')->insert($puzzles);
    }
}
