<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ChapterOneController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\UserSolution;
use App\Http\Middleware\CustomEnsureEmailIsVerified;
use App\Http\Middleware\RedirectIfUserHasPaid;


Route::GET('/', [IndexController::class, 'index'])->middleware('guest');
Route::GET('/profile', [IndexController::class, 'profile'])->middleware(['auth', CustomEnsureEmailIsVerified::class]);
Route::GET('/enter-the-story', [LoginController::class, 'login']);
Route::GET('/the-story', [PuzzleController::class, 'index'])->name('home');
Route::GET('/puzzles/{chapter}/{puzzle}', [PuzzleController::class, 'show']);


// Purchase Routes
Route::middleware(['auth', CustomEnsureEmailIsVerified::class, RedirectIfUserHasPaid::class])->group(function () {

    Route::post('/process-payment', [PurchaseController::class, 'processPayment']);
    Route::get('/checkout', [PurchaseController::class, 'index'])
        ->name('purchase.index');
    Route::get('/checkout/success', [PurchaseController::class, 'success'])
        ->name('checkout.success');

});


require __DIR__.'/auth.php';
