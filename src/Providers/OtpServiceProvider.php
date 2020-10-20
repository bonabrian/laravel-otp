<?php

namespace Bonabrian\Otp\Providers;

use Bonabrian\Otp\Commands\OtpPublishCommand;
use Bonabrian\Otp\OtpService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/otp.php' => config_path('otp.php')
            ], 'otp-config');

            $this->commands([
                OtpPublishCommand::class
            ]);
        }

        $this->app->bind('laravel-otp', function () {
            ['digits' => $digits, 'expiry' => $expiry] = config('otp');
            return (new OtpService(Cache::store()))->setDigits($digits)->setExpiry($expiry);
        });
    }

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/otp.php', 'otp');
    }
}
