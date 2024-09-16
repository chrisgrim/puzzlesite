<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\Puzzle;
use App\Models\Chapter;
use App\Models\User;
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
        $user = auth()->user();
        $progress = $user->progress()->first(); // Get the first progress record
        $lastCompletedOrder = $progress ? $progress->last_completed_puzzle_order : 0;

        $chapters = Chapter::with(['puzzles' => function ($query) use ($lastCompletedOrder) {
            $query->orderBy('order', 'asc')
                  ->where('order', '<=', $lastCompletedOrder + 1);
        }])->get();

        foreach ($chapters as $chapter) {
            foreach ($chapter->puzzles as $puzzle) {
                $puzzle->is_solved = $puzzle->order <= $lastCompletedOrder;
            }
        }

        return view('story', ['chapters' => $chapters]);
    }

	public function show(Puzzle $puzzle)
    {
        $user = auth()->user();
        $solution = $user->solutions()->where('puzzle_id', $puzzle->id)->first();
        $chapter = $puzzle->chapter;
        
        return view("Puzzles.puzzle", [
	        'puzzle' => $puzzle,
	        'chapter' => $chapter,
	        'solution' => $solution,
            'user' => $user,
	    ]);
    }

    public function guess(Request $request, Puzzle $puzzle)
    {
        $user = auth()->user();
        $guess = strtoupper($request->input('guess'));

        $solution = $this->getOrCreateSolution($user, $puzzle, $guess);
        $isCorrect = ($guess === strtoupper($puzzle->solution));

        if ($isCorrect && !$solution->solved) {
            $this->updateProgress($user, $puzzle);
            $solution->solved = true;
        }

        $solution->save();

        return response()->json([
            'message' => $isCorrect ? 'Correct! You solved the puzzle.' : 'Incorrect guess, try again.',
            'solution' => $solution,
        ], 200);
    }



	private function getOrCreateSolution($user, $puzzle, $guess)
    {
        $solution = $user->solutions()->firstOrNew(['puzzle_id' => $puzzle->id]);

        if (!$solution->exists) {
            $solution->guesses = [];
        }

        $existingGuesses = $solution->guesses ?? [];
        if (!in_array($guess, $existingGuesses)) {
            $solution->guesses = array_merge($existingGuesses, [$guess]);
        }

        return $solution;
    }

	private function updateProgress($user, Puzzle $puzzle)
    {
        $user->progress()->updateOrCreate(
	        ['user_id' => $user->id],
	        ['last_completed_puzzle_order' => $puzzle->order]
	    );
    }

	protected function userCanAccessPuzzle(User $user, Puzzle $puzzle)
    {
        $progress = $user->progress;

        // If there's no progress, user can only access the first puzzle
        if (!$progress) {
            return $puzzle->order === 1;
        }

        // User can access this puzzle if it's the next one or any previous puzzle
        return $puzzle->order <= $progress->last_completed_puzzle_order + 1;
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
