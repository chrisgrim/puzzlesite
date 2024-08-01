<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
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
	    
	    return view("Puzzles.Chapter{$chapter->id}.puzzle{$puzzle->id}", [
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
        $progress->puzzle_id = $puzzle->id;
        $progress->save();
	    
	}

	protected function userCanAccessPuzzle($user, $chapter, $puzzle)
	{
	    $progress = $user->progress()->first(); // Assuming there's always a progress record

	    // Same chapter, next puzzle
	    if ($chapter->id == $progress->chapter_id && $puzzle->id <= $progress->puzzle_id + 1) {
	        return true;
	    }

	    // Next chapter, first puzzle
	    if ($chapter->id == $progress->chapter_id + 1 && $puzzle->id == 1) {
	        $lastPuzzleOrderInPrevChapter = Puzzle::where('chapter_id', $progress->chapter_id)->max('id');
	        return $progress->puzzle_id == $lastPuzzleOrderInPrevChapter;
	    }

	    // Special case: allow access to the first puzzle of the first chapter
	    if ($chapter->id == 1 && $puzzle->id == 1) {
	        return true;
	    }

	    return false;
	}

	private function validateGuessWithChatGPT($guess, $solution)
	{
	    $client = new \GuzzleHttp\Client();
	    $apiKey = env('CHATGPT_API_KEY'); // Make sure to set your ChatGPT API key in the .env file

	    $response = $client->post('https://api.openai.com/v1/chat/completions', [
	        'headers' => [
	            'Authorization' => 'Bearer ' . $apiKey,
	            'Content-Type' => 'application/json',
	        ],
	        'json' => [
	            'model' => 'gpt-4o-mini',
	            'messages' => [
	                [
	                    'role' => 'system',
	                    'content' => 'You are a helpful teachers assistant that checks if user guesses match the given solution. Dont give them advice. Keep your response short.'
	                ],
	                [
	                    'role' => 'user',
	                    'content' => "The solution is \"$solution\". The guess is \"$guess\". Is the guess correct? Provide feedback on the guess without revealing the solution."
	                ]
	            ],
	            'max_tokens' => 50,
	            'temperature' => 0.0,
	        ],
	    ]);

	    $body = json_decode((string) $response->getBody(), true);
	    $chatGPTResponse = trim($body['choices'][0]['message']['content']);

	    // Sanitize the response to remove any unintended solution revelations
	    if (stripos($chatGPTResponse, $solution) !== false) {
	        $chatGPTResponse = 'incorrect - The guess is incorrect and not close enough. Try again.';
	    }

	    return $chatGPTResponse;
	}

}
