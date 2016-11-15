<?php

namespace Guzzle;

use Zend\Feed\Reader\Http\ResponseInterface;

class GuzzleResponse implements ResponseInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
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
