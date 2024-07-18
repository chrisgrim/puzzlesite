<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\AdminController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/admin/chapters', [AdminController::class, 'getChapter']);
Route::post('/admin/chapter', [AdminController::class, 'addChapter']);
Route::delete('/admin/chapter', [AdminController::class, 'deleteChapter']);
Route::put('/admin/chapters/{chapter}', [AdminController::class, 'updateChapter']);
Route::put('/admin/chapters/{chapter}/reorder', [AdminController::class, 'reorderChapter']);

Route::post('/admin/puzzles', [AdminController::class, 'addPuzzle']);
Route::delete('/admin/puzzles', [AdminController::class, 'deletePuzzle']);
Route::put('/admin/puzzles/{puzzle}', [AdminController::class, 'updatePuzzle']);

Route::POST('/puzzles/{chapter}/{order}/guess', [PuzzleController::class, 'guess'])->middleware('auth:sanctum');
