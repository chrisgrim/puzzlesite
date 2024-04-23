<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ChapterOneController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\UserSolution;


Route::GET('/', [IndexController::class, 'index'])->middleware('guest');
Route::GET('/profile', [IndexController::class, 'profile'])->middleware('auth');

Route::GET('/enter-the-story', [LoginController::class, 'login']);

Route::GET('/the-story', [PuzzleController::class, 'index'])->name('home');
Route::GET('/puzzles/{chapter}/{puzzle}', [PuzzleController::class, 'show']);


// Purchase Routes
Route::post('/process-payment', [PurchaseController::class, 'processPayment'])
    ->middleware('auth');

Route::GET('/checkout', [PurchaseController::class, 'index'])
    ->middleware('auth');

Route::GET('/checkout/success', [PurchaseController::class, 'success'])
    ->middleware('auth')
    ->name('checkout.success');


Route::GET('/checkout', [PurchaseController::class, 'index'])
                // ->middleware('redirectIfPaid')
                ->name('purchase.index');



require __DIR__.'/auth.php';
