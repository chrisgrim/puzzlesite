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

use App\Http\Controllers\BitcoinController;


Route::GET('/', [IndexController::class, 'index'])->middleware('guest');
Route::GET('/profile', [IndexController::class, 'profile'])->middleware(['auth', CustomEnsureEmailIsVerified::class]);
Route::GET('/enter-the-story', [LoginController::class, 'login']);
Route::GET('/the-story', [PuzzleController::class, 'index'])->name('home');
Route::GET('/puzzles/{chapter}/{puzzle}', [PuzzleController::class, 'show']);

Route::GET('/admin', [AdminController::class, 'index']);


// Purchase Routes
Route::middleware(['auth', CustomEnsureEmailIsVerified::class, RedirectIfUserHasPaid::class])->group(function () {
    Route::get('/checkout', [PurchaseController::class, 'index'])->name('purchase.index');
    // Route::post('/create-payment-intent', [PurchaseController::class, 'createPaymentIntent']);
    Route::post('/process-payment', [PurchaseController::class, 'processPayment']);
});


Route::get('/bitcoin/generate-address', [BitcoinController::class, 'generateAddress']);
Route::get('/bitcoin/address-balance', [BitcoinController::class, 'getAddressBalance']);
Route::post('/bitcoin/create-transaction', [BitcoinController::class, 'createTransaction']);
Route::post('/bitcoin/send-transaction', [BitcoinController::class, 'sendTransaction']);
Route::get('/bitcoin/fund-address', [BitcoinController::class, 'fundAddress']);


Route::get('/test-bitcoin-payment', function (Request $request, BitcoinController $bitcoinController) {
    // Hardcoded address and amount for testing
    $newAddress = 'BuE75PQLDiU4LuQCsQLvyub2RJCeQkkizh'; // Replace with a valid address
    $amount = 1000000;

    // Create a request to fund the address
    $fundRequest = new Request([
        'address' => $newAddress,
        'amount' => $amount,
    ]);

    // Call the fundAddress method
    $fundResponse = $bitcoinController->fundAddress($fundRequest);

    // Log the response for debugging
    Log::info('Fund Address Response:', $fundResponse);

    return response()->json($fundResponse);
});




require __DIR__.'/auth.php';
