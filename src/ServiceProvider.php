<?php

namespace Mabasic\Mailchimp;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Mabasic\Mailchimp\Mailchimp;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Mailchimp::class, function ($app) {
            $key = config('services.mailchimp.key');
            $dc = config('services.mailchimp.dc');

            return new Mailchimp($key, $dc);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Mailchimp::class];
    }
}
