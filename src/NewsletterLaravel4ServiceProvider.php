<?php

namespace Spatie\Newsletter;

use Config;
use DrewM\MailChimp\MailChimp;
use Illuminate\Support\ServiceProvider;

class NewsletterLaravel4ServiceProvider extends NewsletterServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('spatie/laravel-newsletter', 'laravel-newsletter', __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\Spatie\Newsletter\Newsletter::class, function ($app) {
            $mailchimp = new Mailchimp($app['config']->get('laravel-newsletter::apiKey'));

            $mailChimp->verify_ssl = $app['config']->get('laravel-newsletter::ssl') || true;

            $configuredLists = NewsletterListCollection::createFromConfig($app['config']->get('laravel-newsletter'));

            return new Newsletter($mailChimp, $configuredLists);
        });

        $this->app->alias(\Spatie\Newsletter\Newsletter::class, 'laravel-newsletter');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\Spatie\Newsletter\Newsletter::class];
    }
}
