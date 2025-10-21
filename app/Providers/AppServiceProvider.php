<?php

namespace App\Providers;

use App\Watermark;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\MessageSelector;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        $path = app_path('Notifications/Channels/FcmChannel.php');
        if (file_exists($path)) {
            require_once $path;
            \Log::info('Included FcmChannel from register(): '.$path);
        } else {
            \Log::error('FcmChannel.php not found at: '.$path);
        }
    
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
                return new \App\Notifications\Channels\FcmChannel();
            });
        });
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
