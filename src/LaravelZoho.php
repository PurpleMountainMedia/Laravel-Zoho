<?php

namespace PurpleMountain\LaravelZoho;

use zcrmsdk\oauth\ZohoOAuth;

class LaravelZoho
{
    protected $zohoAuth;

    public function __construct(ZohoOAuth $zohoAuth)
    {
        $this->zohoAuth = $zohoAuth;
    }

    public function getOauthGrantUrl()
    {
        return $this->zohoAuth::getGrantURL() . '?client_id=' . $this->zohoAuth::getClientID() . '&scope=ZohoCRM.modules.ALL&response_type=code&access_type=offline&redirect_uri=' . $this->zohoAuth::getRedirectURL();
    }
}