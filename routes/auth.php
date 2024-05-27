<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EnsureAuthenticatedController;
use App\Http\Middleware\RedirectIfVerifiedEmail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

Route::post('/authenticate', [EnsureAuthenticatedController::class, 'login'])
                ->middleware('guest')
                ->name('authenicate');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest')
                ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/email/verify', function () { return view('auth.verify-email');})
                ->middleware(['auth', RedirectIfVerifiedEmail::class])->name('verification.notice'); 

Route::post('/email/verification-notification', function (Request $request) { $request->user()->sendEmailVerificationNotification(); return back()->with('message', 'Verification link sent!'); })
            ->middleware(['auth', 'throttle:6,1'])->name('verification.send');
                
Route::get('/password-reset/{token}', function () { return view('auth.reset-password'); })
                ->middleware('guest')
                ->name('password.reset');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])
                ->name('auth.google');

Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);


