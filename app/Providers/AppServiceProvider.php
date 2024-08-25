<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use App\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmail as NotificationsVerifyEmail;
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

    Schema::defaultStringLength(191);
    NotificationsVerifyEmail::toMailUsing(function ($notifiable, $url) {
        return new VerifyEmail($url);
    });
    }
}
