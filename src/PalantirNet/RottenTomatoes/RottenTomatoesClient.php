<?php

/**
 * @file Rotten Tomatoes client object.
 *
 * Borrowed from https://github.com/guzzle/guzzle-rotten-tomatoes
 */

namespace PalantirNet\RottenTomatoes;

use Guzzle\Common\Collection;
use Guzzle\Http\Message\RequestInterface;

class RottenTomatoesClient extends Client
{
    public function __construct($baseUrl = '', $config = null)
    {
        $default = array();
        $required = array('apikey');
        $config = Collection::fromConfig($config, $default, $required);

        parent::__construct($baseUrl, $config);
    }

    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null)
    {
        $request = parent::createRequest($method, $uri, $headers, $body);

        $request->getQuery()->set('apikey', $this->getConfig()->get('apikey'));

        return $request;
    }
}

