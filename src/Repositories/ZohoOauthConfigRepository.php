<?php

namespace PurpleMountain\LaravelZoho\Repositories;

use Carbon\Carbon;
use PurpleMountain\LaravelZoho\Models\ZohoConfig;
use zcrmsdk\oauth\persistence\ZohoOAuthPersistenceInterface;
use zcrmsdk\oauth\utility\ZohoOAuthTokens;

class ZohoOauthConfigRepository implements ZohoOAuthPersistenceInterface
{
    /**
     * Save the Oauth data.
     *
     * @param  \zcrmsdk\oauth\utility\ZohoOAuthTokens $tokens
     * @return \PurpleMountain\LaravelZoho\Models\ZohoConfig
     */
    public function saveOAuthData($tokens)
    {
        return ZohoConfig::create([
            'client_id' => config('laravel-zoho.clients.0.client_id'),
            'user_identifier' => $tokens->getUserEmailId(),
            'access_token' => $tokens->getAccessToken(),
            'refresh_token' => $tokens->getRefreshToken(),
            'access_token_expires_at' => Carbon::createFromTimestamp(substr($tokens->getExpiryTime(), 0, -3))
        ]);
    }

    /**
     * Save the Oauth data.
     *
     * @param  string $userEmailId
     * @return void
     */
    public function deleteOAuthTokens($userEmailId)
    {
        ZohoConfig::where('user_identifier', $userEmailId)->delete();
    }

    /**
     * Get Oauth tokens from the database.
     *
     * @param  string $userEmailId
     * @return \zcrmsdk\oauth\utility\ZohoOAuthTokens $tokens
     */
    public function getOAuthTokens($userEmailId)
    {
        $config = ZohoConfig::where('user_identifier', $userEmailId)->latest()->first();
        $tokens = new ZohoOAuthTokens;

        $tokens->setRefreshToken(optional($config)->refresh_token);
        $tokens->setAccessToken(optional($config)->access_token);
        $tokens->setExpiryTime(optional(optional($config)->access_token_expires_at)->timestamp * 1000);
        $tokens->setUserEmailId(optional($config)->user_identifier);

        return $tokens;
    }
}
