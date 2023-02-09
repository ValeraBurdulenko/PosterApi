<?php

namespace src;

class MenuApi
{
    private $api;

    public function __construct(PosterApiCore $params)
    {
        $this->api = $params;
    }

    public function getCategories($params = array())
    {
        return $this->api->makeApiRequest('menu.getCategories', 'get', $params);
    }

    public function getCategory($params = array())
    {
        return $this->api->makeApiRequest('menu.getCategory', 'get', $params);
    }
}