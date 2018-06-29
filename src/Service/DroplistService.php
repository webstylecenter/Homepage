<?php

namespace App\Service;

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
     * @param array
     */
    public function __construct()
    {
        $this->images = (array) json_decode(file_get_contents($_SERVER['DROP_LOCATION']), true);
    }

    /**
     * @param integer|null $limit
     *
     * @return array
     */
    public function getImages($limit = null)
    {
        return ($limit === null ? $this->images : array_slice($this->images, 0, $limit));
    }
}
