<?php

namespace PurpleMountain\LaravelZoho\Commands;

use Illuminate\Console\Command;
use PurpleMountain\LaravelZoho\Models\ZohoConfig;
use zcrmsdk\oauth\ZohoOAuth;

class RefreshZohoToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:refresh {--force : Whether or not to force the refresh, even if the current token is valid.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh a zoho access token.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = ZohoConfig::where('client_id', config('laravel-zoho.client_id'))->latest()->first();

        if (!$this->option('force')) {
            if ($config->access_token_expires_at >= now()->addMinutes(6)) {
                $this->error('Access token not expired yet, no need to refresh!');
                return;
            }
        }

        $zohoAuth = resolve(ZohoOAuth::class);
        $oAuthClient = $zohoAuth::getClientInstance(); 
        if ($config) {
            $oAuthClient->refreshAccessToken($config->refresh_token, $config->user_identifier);
            $config->delete();
            $this->info('Token refreshed!');
        } else {
            if (config('laravel-zoho.client_id')) {
                $this->error('Unable to locate a config for client id: ' . config('laravel-zoho.client_id'));
            } else {
                $this->error('Unable to refresh! Please ensure a valid client id is set');
            }
        }
    }
}
