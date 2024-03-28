<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;




Route::get('/', [IndexController::class, 'index']);



require __DIR__.'/auth.php';
