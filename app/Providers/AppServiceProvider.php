<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Firebase Factory
        $this->app->singleton('firebase.factory', function ($app) {
            return (new Factory)
                ->withServiceAccount(env('FIREBASE_CREDENTIALS'))
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        });

        // Firebase Database
        $this->app->singleton(Database::class, function ($app) {
            return $app->make('firebase.factory')->createDatabase();
        });

        // Firebase Auth
        $this->app->singleton(Auth::class, function ($app) {
            return $app->make('firebase.factory')->createAuth();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        
        // Log all HTTP requests for debugging
        app('db')->listen(function($query) {
            \Log::info(
                $query->sql,
                ["Bindings" => $query->bindings, "Time" => $query->time]
            );
        });
    }
}
