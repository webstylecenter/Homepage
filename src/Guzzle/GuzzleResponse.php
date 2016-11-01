<?php

namespace Guzzle;

use Zend\Feed\Reader\Http\ResponseInterface;

class GuzzleResponse implements ResponseInterface
{
    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     */
    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return (string) $this->response->getBody();
    }
}
