<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AdminController;
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
Route::GET('/puzzles/{chapter}/{order}', [PuzzleController::class, 'show']);

Route::GET('/admin', [AdminController::class, 'index']);


// Purchase Routes
Route::middleware(['auth', CustomEnsureEmailIsVerified::class, RedirectIfUserHasPaid::class])->group(function () {
    Route::get('/checkout', [PurchaseController::class, 'index'])->name('purchase.index');
    // Route::post('/create-payment-intent', [PurchaseController::class, 'createPaymentIntent']);
    Route::post('/process-payment', [PurchaseController::class, 'processPayment']);
});



require __DIR__.'/auth.php';
