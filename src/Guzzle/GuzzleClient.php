<?php

namespace Guzzle;

use GuzzleHttp\Client;
use Zend\Feed\Reader\Http\ClientInterface;

class GuzzleClient implements ClientInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'verify' => false
        ], $config);

        $client = new Client($config);
        $this->client = $client;
    }

    /**
     * @param string $uri
     *
     * @return GuzzleResponse
     */
    public function get($uri)
    {
        $response  = $this->client->get($uri);
        return new GuzzleResponse($response);
    }
}
