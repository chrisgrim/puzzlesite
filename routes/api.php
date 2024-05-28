<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuzzleController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::POST('/puzzles/{chapter}/{order}/guess', [PuzzleController::class, 'guess'])->middleware('auth:sanctum');
