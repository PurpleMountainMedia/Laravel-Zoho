<?php

namespace PurpleMountain\LaravelZoho\Http\Controllers;

use Illuminate\Http\Request;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\oauth\exception\ZohoOAuthException;

class ZohoOauthCallbackController extends Controller
{
    /**
     * Turn the code into tokens.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ZohoOAuth $zohoAuth)
    {
        try {
            $oAuthClient = $zohoAuth::getClientInstance();
            $grantToken = $request->get('code');
            return redirect()->route('home')->with([
                'status' => 'Successfully authenticated with Zoho!'
            ]);
        } catch (ZohoOAuthException $e) {
            report($e);
            return redirect()->route('home')->with([
                'error' => 'There was an issue authorising the Zoho request, please try again!'
            ]);
        }
    }
}