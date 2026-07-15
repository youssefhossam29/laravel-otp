<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Twilio\Rest\Client as TwilioClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TwilioClient::class, function ($app) {
            return new TwilioClient(
                config('services.twilio.sid'),
                config('services.twilio.token'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
