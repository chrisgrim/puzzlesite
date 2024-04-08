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


Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.index');
Route::post('/purchase', [PurchaseController::class, 'store'])->name('purchase.store');



require __DIR__.'/auth.php';
