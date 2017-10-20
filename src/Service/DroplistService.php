<?php

namespace Service;

/**
 * Class DroplistService
 * @package Service
 */
class DroplistService
{

    /**
     * @var array
     */
    protected $images = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->images = (array) json_decode(file_get_contents($config['droplocation']), true);
    }

    /**
     * @param null $limit
     *
     * @return array
     */
    public function getImages($limit = null)
    {
        return ($limit == null ? $this->images : array_slice($this->images, 0, $limit));
    }
}
