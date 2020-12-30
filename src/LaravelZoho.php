<?php

namespace PurpleMountain\LaravelZoho;

use zcrmsdk\oauth\ZohoOAuth;
use Illuminate\Support\Str;

class LaravelZoho
{
    /**
     * The ZohoAuth instance.
     *
     * @var \zcrmsdk\oauth\ZohoOAuth
     */
    protected $zohoAuth;

    /**
     * The scopes for Oauth2.
     *
     * @var string
     */
    protected $scope;

    /**
     * The user.
     *
     * @var string
     */
    protected $userEmailId;

    /**
     * Kick things off!
     *
     * @param \zcrmsdk\oauth\ZohoOAuth $zohoAuth
     */
    public function __construct(ZohoOAuth $zohoAuth, $client)
    {
        $this->setZohoAuth($zohoAuth);
        $this->setScope($client['default_scope']);
        $this->setUserEmailId($client['user_email']);
        $this->setApiBaseUrl($client['api_base_url']);
    }

    /**
     * Set the ZohoAuth instance.
     *
     * @param \zcrmsdk\oauth\ZohoOAuth $zohoAuth
     * @return \PurpleMountain\LaravelZoho\LaravelZoho
     */
    public function setZohoAuth(ZohoOAuth $zohoAuth)
    {
        $this->zohoAuth = $zohoAuth;
        return $this;
    }

    /**
     * Set the user.
     *
     * @param string $scope
     * @return \PurpleMountain\LaravelZoho\LaravelZoho
     */
    public function setUserEmailId($userEmailId)
    {
        $this->userEmailId = $userEmailId;
        return $this;
    }


    /**
     * Set the scope.
     *
     * @param string $scope
     * @return \PurpleMountain\LaravelZoho\LaravelZoho
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Set the api base url.
     *
     * @param string $apiBaseUrl
     * @return \PurpleMountain\LaravelZoho\LaravelZoho
     */
    public function setApiBaseUrl($apiBaseUrl)
    {
        $this->apiBaseUrl = $apiBaseUrl;
        return $this;
    }

    /**
     * Get the api base url.
     *
     * @return string
     */
    public function getApiBaseUrl()
    {
        return $this->apiBaseUrl;
    }

    /**
     * Get the full url to send the user to approve this request.
     *
     * @return string
     */
    public function getOauthRedirectUrl()
    {
        $state = Str::random(20);
        session(["zoho-user.{$state}" => $this->userEmailId]);
        return $this->zohoAuth::getGrantURL() . '?client_id=' . $this->zohoAuth::getClientID() . "&scope={$this->scope}&response_type=code&access_type=offline&redirect_uri=" . $this->zohoAuth::getRedirectURL() . "&state={$state}";
    }
}