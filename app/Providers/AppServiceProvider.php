<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\Drivers\SendPostTransport;

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
        Mail::extend('sendpost', function (array $config = []) {
            return new SendPostTransport(
                config('sendpost.api_key'),
                config('sendpost.from_email'),
                config('sendpost.from_name')
            );
        });
    }
}
