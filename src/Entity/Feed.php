<?php

namespace Entity;

/**
 * Class Feed
 * @package Entity
 */
class Feed
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $feedUrl;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var string
     */
    protected $feedIcon;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @param $id
     * @param $name
     * @param $feedUrl
     * @param $color
     */
    public function __construct($id, $name, $feedUrl, $color, $feedIcon)
    {
        $this->id           = $id;
        $this->name         = $name;
        $this->feedUrl      = $feedUrl;
        $this->color        = $color;
        $this->feedIcon     = $feedIcon;
        $this->created      = new \DateTime;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * @param string $feedUrl
     */
    public function setFeedUrl($feedUrl)
    {
        $this->feedUrl = $feedUrl;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getFeedIcon()
    {
        return $this->feedIcon;
    }

    /**
     * @param string $feedIcon
     * @return Feed
     */
    public function setFeedIcon($feedIcon)
    {
        $this->feedIcon = $feedIcon;
        return $this;
    }
}
