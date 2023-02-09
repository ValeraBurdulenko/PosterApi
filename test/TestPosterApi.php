<?php

use PHPUnit\Framework\TestCase;
use src\PosterApi;

class TestPosterApi extends TestCase
{
    public function test_GetCategory() {
        PosterApi::init([
            'account_name' => 'WyS',
            'access_token' => '52597417521764476af9e284bdf12222',
        ]);

        $result = (object)PosterAPI::menu()->getCategory([
            'category_id' => 1
        ]);

        $this->assertEquals('Coffee', $result->response->category_name);
    }

    public function test_GetOauthToken() {
        PosterApi::init([
            'application_id' => 2753,
            'application_secret' => 'd61ca9458f9f6863269eec36e35114be',
            'redirect_uri' => 'https://mysite.com/poster/auth',
        ]);

        $result = (object)PosterAPI::auth()->getOauthToken($_GET['account'], $_GET['code']);
        var_dump($result);

        $this->assertEquals($result->access_token, '52597417521764476af9e284bdf12222');
    }
}