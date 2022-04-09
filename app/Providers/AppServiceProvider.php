<?php

namespace App\Providers;

use App\Services\BugTracker\Providers\SentryTracker;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //URL::forceScheme('https');

        $this->app->bind('bugtracker', function () {
            return app(SentryTracker::class);
        });

        /*
        *  Set up locale and locale_prefix if other language is selected
        */
        if (in_array(Request::segment(1), Config::get('app.alt_langs'))) {

            App::setLocale(Request::segment(1));
            Config::set('app.locale_prefix', Request::segment(1));
        }
    }
}
