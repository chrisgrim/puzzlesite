<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ChapterOneController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\LoginController;




Route::get('/', [IndexController::class, 'index']);
Route::get('/user/login', [LoginController::class, 'login']);
Route::get('/chapter-one/puzzle-one', [ChapterOneController::class, 'puzzleOne']);
Route::get('/chapter-one/puzzle-two', [ChapterOneController::class, 'puzzleTwo']);
Route::get('/chapter-one/puzzle-three', [ChapterOneController::class, 'puzzleThree']);


// Purchase Routes
Route::post('/process-payment', [PurchaseController::class, 'processPayment'])->middleware('auth');
Route::get('/checkout', [PurchaseController::class, 'index'])
                ->name('purchase.index');
Route::get('/checkout/success', [PurchaseController::class, 'success'])->name('checkout.success');


Route::get('/checkout', [PurchaseController::class, 'index'])
                // ->middleware('redirectIfPaid')
                ->name('purchase.index');



require __DIR__.'/auth.php';
