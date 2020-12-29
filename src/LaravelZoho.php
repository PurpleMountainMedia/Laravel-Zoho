<?php

namespace PurpleMountain\LaravelZoho;

use zcrmsdk\oauth\ZohoOAuth;

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
     * Kick things off!
     *
     * @param \zcrmsdk\oauth\ZohoOAuth $zohoAuth
     */
    public function __construct(ZohoOAuth $zohoAuth, $scope)
    {
        $this->setZohoAuth($zohoAuth);
        $this->scope($scope);
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
     * Set the scope.
     *
     * @param string $scope
     * @return \PurpleMountain\LaravelZoho\LaravelZoho
     */
    public function scope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Get the full url to send the user to approve this request.
     *
     * @return string
     */
    public function getOauthRedirectUrl($userEmailId)
    {
        $state = Str::random(20);
        session(["zoho-user.{$state}" => $userEmailId]);
        return $this->zohoAuth::getGrantURL() . '?client_id=' . $this->zohoAuth::getClientID() . "&scope={$this->scope}&response_type=code&access_type=offline&redirect_uri=" . $this->zohoAuth::getRedirectURL() . "&state={$state}";
    }
}