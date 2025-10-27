<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register SendGrid transport
        $this->app->afterResolving(MailManager::class, function (MailManager $manager) {
            $manager->extend('sendgrid', function () {
                $apiKey = config('services.sendgrid.api_key');
                return new \Illuminate\Mail\Transport\ApiTransport(
                    new \GuzzleHttp\Client([
                        'base_uri' => 'https://api.sendgrid.com/v3/',
                        'headers' => [
                            'Authorization' => 'Bearer '.$apiKey,
                        ],
                    ]),
                    fn (array $message) => [
                        'method' => 'POST',
                        'path' => 'mail/send',
                        'payload' => $message,
                    ]
                );
            });
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use safe default string length for older MySQL index limits
        Schema::defaultStringLength(191);
        
        // Set upload limits for video uploads
        if (file_exists(config_path('upload_limits.php'))) {
            require config_path('upload_limits.php');
        }
    }
}
