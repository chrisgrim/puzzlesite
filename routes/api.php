<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BitcoinController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/admin/chapters', [AdminController::class, 'getChapter']);
Route::post('/admin/chapter', [AdminController::class, 'addChapter']);
Route::delete('/admin/chapter', [AdminController::class, 'deleteChapter']);
Route::put('/admin/chapters/{chapter}', [AdminController::class, 'updateChapter']);

Route::post('/admin/puzzles', [AdminController::class, 'addPuzzle']);
Route::delete('/admin/puzzles', [AdminController::class, 'deletePuzzle']);
Route::put('/admin/puzzles/{puzzle}', [AdminController::class, 'updatePuzzle']);

Route::POST('/puzzles/{chapter}/{puzzle}/guess', [PuzzleController::class, 'guess'])->middleware('auth:sanctum');


Route::post('/create-bitcoin-order', [BitcoinController::class, 'store']);
Route::get('/get-pending-order', [BitcoinController::class, 'getPendingOrder']);
Route::get('/bitcoin-price', [BitcoinController::class, 'price']);
Route::post('/blockcypher/webhook', [BlockCypherWebhookController::class, 'handle']);
Route::post('/register-blockcypher-webhook', [BlockCypherWebhookController::class, 'registerWebhook']);




