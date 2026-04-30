<?php

namespace App\Providers;

use App\Contracts\EmailNotificationInterface;
use App\Services\SendGridService;
use App\Services\MailgunService;
use App\Services\SMTPEmailService;
use Illuminate\Support\ServiceProvider;

class EmailNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register email notification services.
     */
    public function register(): void
    {
        $this->app->bind(
            EmailNotificationInterface::class,
            function () {
                $provider = env('EMAIL_PROVIDER', 'smtp');

                if ($provider === 'sendgrid') {
                    return new SendGridService();
                }

                if ($provider === 'mailgun') {
                    return new MailgunService();
                }

                return new SMTPEmailService();
            }
        );
    }

    /**
     * Bootstrap email notification services.
     */
    public function boot(): void
    {
        //
    }
}
