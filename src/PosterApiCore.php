<?php

namespace src;


class PosterApiCore
{
    // without access_token
    public $application_id;
    public $application_secret;
    public $redirect_uri;

    // with access_token
    public $account_name;
    public $access_token;

    // defaults
    public $domain = 'joinposter.com';
    public $protocol = 'https';
    public $result_format = 'json';


    public $base_api_url;
    public $account_api_url;


    public function __construct($config = [])
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        $this->base_api_url = $this->protocol . '://' . $this->domain . '/api/';
        $this->account_api_url = $this->protocol . '://{account_name}.' . $this->domain . '/api/';

        if (!$this->account_name) {
            $this->access_token = '';
        }

        if (!$this->application_secret || !$this->redirect_uri) {
            $this->application_id = '';
        }

        if (!$this->access_token && !$this->application_id) {
            throw new \Exception('Missing access_token or application_id');
        }
        if (!$this->access_token && !$this->application_secret) {
            throw new \Exception('Missing access_token or application_secret');
        }
        if (!$this->access_token && !$this->redirect_uri) {
            throw new \Exception('Missing access_token or redirect_uri');
        }
    }

    function sendRequest($url, $type = 'get', $params = [], $json = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($type == 'post' || $type == 'put') {
            curl_setopt($ch, CURLOPT_POST, true);

            if ($json) {
                $params = json_encode($params);

                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($params)
                ]);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Poster (http://joinposter.com)');

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function makeApiRequest($method, $type = 'get', $params = '')
    {
        $getParams = [
            'format' => $this->result_format,
        ];

        if ($this->access_token) {
            $getParams['token'] = $this->access_token;
        }

        $arguments = $params ? $params : [];

        if ($type == 'get') {
            $getParams = array_merge($getParams, $arguments);
            $postParams = '';
        } else {
            $postParams = $arguments;
        }

        $request_url = self::getApiUrl() . $method . '?' . self::prepareGetParams($getParams);
        $result = self::sendRequest($request_url, $type, $postParams);

        return json_decode($result);
    }


    public function auth()
    {
        return new AuthAPI($this);
    }

    public function menu()
    {
        return new MenuAPI($this);
    }


    public function setAccessToken($accessToken)
    {
        $this->access_token = $accessToken;
    }

    public function setAccountName($accountName)
    {
        $this->account_name = $accountName;
    }

    public function getApplicationId()
    {
        return $this->application_id;
    }

    public function getApplicationSecret()
    {
        return $this->application_secret;
    }

    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    public function getApiUrl()
    {
        if ($this->account_name) {
            return str_replace('{account_name}', $this->account_name, $this->account_api_url);
        } else {
            return $this->base_api_url;
        }
    }

    public function prepareGetParams($params)
    {
        $result = [];

        foreach ($params as $key => $value) {
            $result[] = $key . '=' . urlencode($value);
        }

        return implode('&', $result);
    }
}