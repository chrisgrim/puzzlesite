<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use App\Models\Puzzle;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Custom binding for Puzzle
        Route::bind('order', function ($value, $route) {
            $chapterId = $route->parameter('chapter');
            return Puzzle::where('chapter_id', $chapterId)->where('order', $value)->firstOrFail();
        });
    }
}
