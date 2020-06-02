<?php

namespace PurpleMountain\LaravelZoho;

use Illuminate\FileSystem\FileSystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use PurpleMountain\LaravelZoho\Commands\GetZohoRedirectUrl;
use PurpleMountain\LaravelZoho\Commands\RefreshZohoToken;
use PurpleMountain\LaravelZoho\LaravelZoho;
use PurpleMountain\LaravelZoho\Providers\EventServiceProvider;
use PurpleMountain\LaravelZoho\Repositories\ZohoOauthConfigRepository;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;

class ServiceProvider extends BaseServiceProvider
{
    /** 
     * Put together the path to the config file.
     *
     * @return string
     */
    private function configPath(): string
    {
        return __DIR__.'/../config/' . $this->shortName() . '.php';
    }

    /** 
     * Get the short name for this package.
     *
     * @return string
     */
    private function shortName(): string
    {
        return 'laravel-zoho';
    }


    /**
     * Bootstrap the package.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleRoutes();
        $this->handleConfigs();

        if (env('APP_ENV') === 'local') {
            $this->handleMigrations();
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                RefreshZohoToken::class,
                GetZohoRedirectUrl::class
            ]);
        }
    }

    /**
     * Register anything this package needs.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), $this->shortName());
        $this->app->register(EventServiceProvider::class);

        $this->app->bind(ZohoOAuth::class, function ($app) {
            $this->initZohoClient();
            return new ZohoOAuth;
        });

        $this->app->bind(ZCRMRestClient::class, function ($app) {
            $this->initZohoClient();
            return ZCRMRestClient::class;
        });

        $this->app->bind(LaravelZoho::class, function ($app) {
            return new LaravelZoho($app->make(ZohoOAuth::class), config('laravel-zoho.default_scope'));
        });

        $this->app->bind('laravel-zoho', function ($app) {
            return $app->make(LaravelZoho::class);
        });
    }

    /** 
     * Register any migrations.
     *
     * @return void
     */
    private function handleMigrations()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/2020_06_02_122750_create_zoho_configs_table.php.stub' => database_path('migrations/2020_06_02_122750_create_zoho_configs_table.php')
        ], $this->shortName() . '-migrations');
    }

    /** 
     * Register any routes this package needs.
     *
     * @return void
     */
    private function handleRoutes()
    {
        Route::group([
            'name' => $this->shortName(),
            'namespace' => 'PurpleMountain\LaravelZoho\Http\Controllers',
            'middleware' => ['web']
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /** 
     * Register any config files this package needs.
     *
     * @return void
     */
    private function handleConfigs()
    {
        $this->publishes([
            $this->configPath(),
            $this->shortName() . '-config'
        ]);
    }

    /**
     * Initialize the zoho client.
     *
     * @return \ZCRMRestClient
     */
    private function initZohoClient()
    {
        return ZCRMRestClient::initialize([
            'client_id' => config('laravel-zoho.client_id'),
            'client_secret' => config('laravel-zoho.client_secret'),
            'redirect_uri' => config('laravel-zoho.redirect_uri'),
            'currentUserEmail' => config('laravel-zoho.user_email'),
            'persistence_handler_class_name' => ZohoOauthConfigRepository::class,
            'persistence_handler_class' => '/Users/christianbraybrooke/local_sites/engine.havebike/packages/laravel-zoho/src/Repositories/ZohoOauthConfigRepository.php'
        ]);
    }
}