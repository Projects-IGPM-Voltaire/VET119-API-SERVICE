<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {


            $appUrl = env('WEB_APP_URL');

            $deconstructedUrl = explode("/api/" ,$url);
            $apiUrl = $deconstructedUrl[0];

            // $url = str_replace('http://127.0.0.1:8000/api/email/verify', 'http://localhost:9000/#/email-verification', $url);

            $url = str_replace($apiUrl . '/api/email/verify', $appUrl . '#/email-verification', $url);

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });
    }
}
