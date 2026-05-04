<?php

namespace App\Providers;

use App\Notifications\Channels\FcmChannel;
use App\Watermark;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\MessageSelector;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Notifications/Channels/FcmChannel.php');

        $this->app->singleton(Watermark::class, function () {
            return new Watermark($this->app['config']->get('biologer.watermark'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fixes issue with MySQL indexed string column size.
        Schema::defaultStringLength(191);

        // Manually load FcmChannel since composer dump-autoload cannot run
        require_once base_path('app/Notifications/Channels/FcmChannel.php');

        Collection::macro('latest', function () {
            return $this->sortByDesc('created_at');
        });

        Collection::macro('getCollect', function ($key, $default = null) {
            return new static($this->get($key, $default));
        });

        Paginator::defaultView('pagination::bulma');
        Paginator::defaultSimpleView('pagination::bulma');

        $this->setCustomTranslationMessageSelector();

        // Register FCM notification channel
        $this->app->afterResolving(ChannelManager::class, function ($manager) {
            $manager->extend('fcm', function () {
                return new FcmChannel();
            });
        });

        Passport::enablePasswordGrant();

    }

    /**
     * Extend Laravel's Message Selector to add support for "sr-Latn".
     */
    private function setCustomTranslationMessageSelector()
    {
        Lang::setSelector(new class extends MessageSelector {
            public function getPluralIndex($locale, $number)
            {
                if ($locale === 'sr-Latn') {
                    $locale = 'sr';
                }

                return parent::getPluralIndex($locale, $number);
            }
        });
    }

    protected function customizeVerificationMail()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new MailMessage)
                ->subject(Lang::get('Verify Email Address'))
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->action(Lang::get('Verify Email Address'), $verificationUrl)
                ->line(Lang::get('If you did not create an account, no further action is required.'));
        });
    }

}
