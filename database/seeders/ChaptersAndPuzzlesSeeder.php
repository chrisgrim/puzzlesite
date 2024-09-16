<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChaptersAndPuzzlesSeeder extends Seeder
{
    public function run(): void
    {
        // Define chapters
        $chapters = [
            ['title' => 'Chapter 1: The Beginning', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Chapter 2: Deepening Mystery', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Chapter 3: Unexpected Turns', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Chapter 4: The Final Challenge', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        // Insert chapters
        DB::table('chapters')->insert($chapters);
        $chapterIds = DB::table('chapters')->pluck('id');

        // Define puzzles with fixed slugs
        $puzzles = [
            // Chapter 1
            ['slug' => 'KCAFT', 'title' => 'Apartment Puzzle', 'chapter_id' => $chapterIds[0]],
            ['slug' => 'LMNOP', 'title' => 'Alarm Clock', 'chapter_id' => $chapterIds[0]],
            ['slug' => 'QRSTU', 'title' => 'Crossword', 'chapter_id' => $chapterIds[0]],
            ['slug' => 'VWXYZ', 'title' => 'Motivational', 'chapter_id' => $chapterIds[0]],
            ['slug' => 'ABCDE', 'title' => 'Last Puzzle', 'chapter_id' => $chapterIds[0]],
            ['slug' => 'AMETA', 'title' => 'Meta Chapter 1', 'chapter_id' => $chapterIds[0]],

            // Chapter 2
            ['slug' => 'FGHIJ', 'title' => 'Morning Puzzle', 'chapter_id' => $chapterIds[1]],
            ['slug' => 'KLMNO', 'title' => 'Graffiti Wall', 'chapter_id' => $chapterIds[1]],
            ['slug' => 'PQRST', 'title' => 'Sherlock Chromes', 'chapter_id' => $chapterIds[1]],
            ['slug' => 'UVWXY', 'title' => 'The Busker', 'chapter_id' => $chapterIds[1]],
            ['slug' => 'ZABCD', 'title' => 'Trolly Ads', 'chapter_id' => $chapterIds[1]],
            ['slug' => 'BMETA', 'title' => 'Chapter 2 Meta (Website)', 'chapter_id' => $chapterIds[1]],

            // Chapter 3
            ['slug' => 'EFGHI', 'title' => 'Elevator Display', 'chapter_id' => $chapterIds[2]],
            ['slug' => 'JKLMN', 'title' => 'Cryptic Writing', 'chapter_id' => $chapterIds[2]],
            ['slug' => 'OPQRS', 'title' => 'Screen Saver', 'chapter_id' => $chapterIds[2]],
            ['slug' => 'TUVWX', 'title' => 'Voice Mail', 'chapter_id' => $chapterIds[2]],
            ['slug' => 'YZABC', 'title' => 'Secrets of the Simulation', 'chapter_id' => $chapterIds[2]],
            ['slug' => 'CMETA', 'title' => 'Chapter 3 Meta HR Email ', 'chapter_id' => $chapterIds[2]],

            // Chapter 4
            ['slug' => 'DEFGH', 'title' => 'The Ultimate Riddle', 'chapter_id' => $chapterIds[3]],
            ['slug' => 'IJKLM', 'title' => 'Convergence of Clues', 'chapter_id' => $chapterIds[3]],
            ['slug' => 'NOPQR', 'title' => 'The Final Piece', 'chapter_id' => $chapterIds[3]],
            ['slug' => 'STUVW', 'title' => 'Beyond the Veil', 'chapter_id' => $chapterIds[3]],
            ['slug' => 'XYZAB', 'title' => 'The Grand Revelation', 'chapter_id' => $chapterIds[3]],
        ];

        // Insert puzzles
        foreach ($puzzles as $index => $puzzle) {
            DB::table('puzzles')->insert([
                'chapter_id' => $puzzle['chapter_id'],
                'title' => $puzzle['title'],
                'slug' => $puzzle['slug'],
                'description' => "Unravel the mystery of {$puzzle['title']}. Each step brings you closer to the truth.",
                'solution' => "HELLO",
                'difficulty' => rand(1, 5),
                'order' => $index + 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}