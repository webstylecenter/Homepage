<?php

namespace Service;

/**
 * Class DroplistService
 * @package Service
 */
class DroplistService
{
    const DROP_URL = 'http://pvd.onl/api.php?hash=4deaf5a24b4ee6f6d0135f1bc2214bc3';

    /**
     * @var array
     */
    protected $images = [];

    /**
     */
    public function __construct()
    {
        $this->images = (array)json_decode(file_get_contents(self::DROP_URL), true);
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }
}
