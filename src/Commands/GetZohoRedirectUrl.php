<?php

namespace PurpleMountain\LaravelZoho\Commands;

use Illuminate\Console\Command;
use LaravelZoho;

class GetZohoRedirectUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the Zoho redirect url to authorize this app.';

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
        $this->info(LaravelZoho::getOauthRedirectUrl());
    }
}
