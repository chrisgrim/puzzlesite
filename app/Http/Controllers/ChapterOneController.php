<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChapterOneController extends Controller
{
    public function puzzleOne()
    {
        return view('ChapterOne.puzzle-one'); // Assuming you have an index.blade.php file in your resources/views directory
    }

    public function puzzleTwo()
    {
        return view('ChapterOne.puzzle-two'); // Assuming you have an index.blade.php file in your resources/views directory
    }
    public function puzzleThree()
    {
        return view('ChapterOne.puzzle-three'); // Assuming you have an index.blade.php file in your resources/views directory
    }
}
