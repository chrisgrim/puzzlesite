<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use App\Models\Chapter;


class PuzzleController extends Controller
{

	public function __construct()
	{
	    $this->middleware('auth');
	}

	public function index()
    {
        return view('story');
    }

	public function show(Chapter $chapter, Puzzle $puzzle)
	{
	    $user = auth()->user(); // Ensures the user is authenticated and retrieved
	    $solution = $user->solutions()->where('puzzle_id', $puzzle->id)->first();

	    if (!$this->userCanAccessPuzzle($user, $chapter, $puzzle)) {
	        return redirect()->back();
	    }
	    
	    return view("Puzzles.puzzle-{$chapter->id}-{$puzzle->order}", [
            'puzzle' => $puzzle,
            'chapter' => $chapter,
            'solution' => $solution,
        ]);

	}

    public function guess(Request $request, Chapter $chapter, Puzzle $puzzle)
	{
		return $puzzle;
		$user = auth()->user();
	    $guess = strtoupper($request->input('guess'));
	    $isCorrect = ($guess === strtoupper($puzzle->solution));

	    $solution = $user->solutions()->firstOrNew([
	        'puzzle_id' => $puzzle->id
	    ]);

	    // If it's a new entry, initialize guesses
	    if (!$solution->exists) {
	        $solution->guesses = [];
	    }

	    // Append new guess to existing guesses and update solved status
	    $solution->guesses = array_merge($solution->guesses ?? [], [$guess]);
	    $solution->solved = $isCorrect;
	    $solution->save();


	    if ($isCorrect) {
		    $progress = $user->progress()->first();
	        $progress->chapter_id = $chapter->id;
	        $progress->puzzle_order = $puzzle->order;
	        $progress->save();
		}

	    return response()->json([
		    'message' => $isCorrect ? 'Correct! You solved the puzzle.' : 'Incorrect guess, try again.',
		    'solution' => $solution,  // Include the entire solution object in the response
		], 200);

	}

	protected function userCanAccessPuzzle($user, $chapter, $puzzle)
	{
	    $progress = $user->progress()->first(); // Assuming there's always a progress record

	    // Same chapter, next puzzle
	    if ($chapter->id == $progress->chapter_id && $puzzle->order == $progress->puzzle_order + 1) {
	        return true;
	    }

	    // Next chapter, first puzzle
	    if ($chapter->id == $progress->chapter_id + 1 && $puzzle->order == 1) {
	        $lastPuzzleOrderInPrevChapter = Puzzle::where('chapter_id', $progress->chapter_id)->max('order');
	        return $progress->puzzle_order == $lastPuzzleOrderInPrevChapter;
	    }

	    // Special case: allow access to the first puzzle of the first chapter
	    if ($chapter->id == 1 && $puzzle->order == 1) {
	        return true;
	    }

	    return false;
	}
}
