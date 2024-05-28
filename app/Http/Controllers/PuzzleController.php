<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Puzzle;
use App\Models\Chapter;
use App\Http\Middleware\EnsureUserHasPaid;
use App\Http\Middleware\CustomEnsureEmailIsVerified;


class PuzzleController extends Controller
{

	public function __construct()
	{
		$this->middleware(['auth', CustomEnsureEmailIsVerified::class, EnsureUserHasPaid::class]);
	}

	public function index()
	{
	    $user = auth()->user(); // Assuming you're using Laravel's built-in authentication
	    $progress = $user->progress()->first(); // Assuming there's always a progress record

	    // Get all chapters with their puzzles
	    $chapters = Chapter::with('puzzles')->get();

	    // Filter chapters to include only those with at least one accessible puzzle
	    $accessibleChapters = $chapters->filter(function ($chapter) use ($user) {
	        $hasAccessiblePuzzle = false;
	        foreach ($chapter->puzzles as $puzzle) {
	            $puzzle->is_accessible = $this->userCanAccessPuzzle($user, $chapter, $puzzle);
	            $puzzle->is_solved = $user->solutions()->where('puzzle_id', $puzzle->id)->where('solved', true)->exists();
	            if ($puzzle->is_accessible) {
	                $hasAccessiblePuzzle = true;
	            }
	        }
	        return $hasAccessiblePuzzle;
	    });

	    return view('story', ['chapters' => $accessibleChapters->values()]);
	}

	public function show(Chapter $chapter, Puzzle $puzzle)
	{
	    $user = auth()->user(); // Ensures the user is authenticated and retrieved
	    $solution = $user->solutions()->where('puzzle_id', $puzzle->id)->first();

	    if (!$this->userCanAccessPuzzle($user, $chapter, $puzzle)) {
	        return redirect()->back();
	    }
	    
	    return view("Puzzles.Chapter{$chapter->id}.puzzle{$puzzle->order}", [
            'puzzle' => $puzzle,
            'chapter' => $chapter,
            'solution' => $solution,
        ]);

	}

    public function guess(Request $request, Chapter $chapter, Puzzle $puzzle)
	{
	    $user = auth()->user();
	    $guess = strtoupper($request->input('guess'));

	    $solution = $this->getOrCreateSolution($user, $puzzle, $guess);
	    $isCorrect = ($guess === strtoupper($puzzle->solution));

	    // Update solved status and save if newly solved
	    if ($isCorrect && !$solution->solved) {
	        $this->updateProgress($user, $chapter, $puzzle);
	        $solution->solved = true;
	    }

	    $solution->save();

	    return response()->json([
	        'message' => $isCorrect ? 'Correct! You solved the puzzle.' : 'Incorrect guess, try again.',
	        'solution' => $solution,  // Include the entire solution object in the response
	    ], 200);
	}

	private function getOrCreateSolution($user, $puzzle, $guess)
	{
	    $solution = $user->solutions()->firstOrNew(['puzzle_id' => $puzzle->id]);

	    // If it's a new entry, initialize guesses
	    if (!$solution->exists) {
	        $solution->guesses = [];
	    }

	    // Check if the guess already exists
	    $existingGuesses = $solution->guesses ?? [];
	    if (!in_array($guess, $existingGuesses)) {
	        // Append new guess to existing guesses
	        $solution->guesses = array_merge($existingGuesses, [$guess]);
	    }

	    return $solution;
	}

	private function updateProgress($user, $chapter, $puzzle)
	{
	    $progress = $user->progress()->first();

        $progress->chapter_id = $chapter->id;
        $progress->puzzle_order = $puzzle->order;
        $progress->save();
	    
	}

	protected function userCanAccessPuzzle($user, $chapter, $puzzle)
	{
	    $progress = $user->progress()->first(); // Assuming there's always a progress record

	    // Same chapter, next puzzle
	    if ($chapter->id == $progress->chapter_id && $puzzle->order <= $progress->puzzle_order + 1) {
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
