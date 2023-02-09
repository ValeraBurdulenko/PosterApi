<?php

namespace src;


class AuthApi
{
    public $api;

    public function __construct(PosterApiCore $params)
    {
        $this->api = $params;
    }

    public function getOauthToken($account_name, $code)
    {
        $this->api->setAccountName($account_name);

        $request_url = $this->api->getApiUrl() . 'auth/access_token';
        $auth = [
            'application_id' => $this->api->getApplicationId(),
            'application_secret' => $this->api->getApplicationSecret(),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->api->getRedirectUri(),
            'code' => $code,
        ];

        $result = $this->api->sendRequest($request_url, 'post', $auth);
        $result = (object)json_decode($result);

        if (isset($result->access_token) && $result->access_token) {
            $this->api->setAccessToken($result->access_token);
        }

        return $result;
    }
}