<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Puzzle;

class AdminController extends Controller
{

    public function index()
    {
        return view('Admin.index');
    }

    public function addPuzzle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'solution' => 'nullable|string|max:255',
            'difficulty' => 'nullable|integer',
            'chapter_id' => 'required|exists:chapters,id',
            'order' => 'required|integer|unique:puzzles,order'
        ]);

        $slug = strtoupper(Str::random(5));
        while (Puzzle::where('slug', $slug)->exists()) {
            $slug = strtoupper(Str::random(5));
        }

        $puzzle = Puzzle::create(array_merge($request->all(), ['slug' => $slug]));
        return response()->json($puzzle, 201);
    }

    public function updatePuzzle(Request $request, Puzzle $puzzle)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'solution' => 'nullable|string|max:255',
            'difficulty' => 'nullable|integer',
            'order' => 'required|integer|unique:puzzles,order,' . $puzzle->id
        ]);

        $puzzle->update($request->all());
        return response()->json($puzzle, 200);
    }

    public function addChapter(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $chapter = Chapter::create($request->all());

        return response()->json($chapter, 200);
    }

    public function updateChapter(Request $request, Chapter $chapter)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $chapter->update($request->all());

        return response()->json($chapter, 200);
    }

    public function getChapter()
    {
        $chapters = Chapter::with('puzzles')->get();
        return response()->json($chapters);
    }
}
