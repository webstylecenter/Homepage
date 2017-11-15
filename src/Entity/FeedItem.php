<?php

namespace Entity;

/**
 * Class FeedItem
 * @package Entity
 */
class FeedItem
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var \DateTime
     */
    protected $dateAdded;

    /**
     * @var boolean
     */
    protected $viewed = false;

    /**
     * @var integer
     */
    protected $feedId;

    /**
     * @var boolean
     */
    protected $pinned = false;

    /**
     * @var integer
     */
    protected $color;

    /**
     * @var string
     */
    protected $feedName;

    /**
     * @param integer $id
     * @param string $title
     * @param string $description
     * @param string $url
     * @param integer $feedId
     */
    public function __construct($id, $title, $description, $url, $feedId)
    {
        $this->id          = $id;
        $this->title       = $title;
        $this->description = $description;
        $this->url         = $url;
        $this->dateAdded   = new \DateTime;
        $this->feedId      = $feedId;
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
     *
     * @return FeedItem
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return FeedItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return FeedItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function getShortDescription($length = 120)
    {
        if (false === ($breakpoint = mb_strpos($this->description, ' ', $length, 'UTF-8'))) {
            return $this->description;
        }

        return rtrim(mb_substr($this->description, 0, $breakpoint, 'UTF-8')) . '...';
    }
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return FeedItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     *
     * @return FeedItem
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewed()
    {
        return $this->viewed;
    }

    /**
     * @param bool $viewed
     *
     * @return FeedItem
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * @return int
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    /**
     * @param int $feedId
     *
     * @return FeedItem
     */
    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPinned()
    {
        return !!$this->pinned;
    }

    /**
     * @param bool $pinned
     *
     * @return FeedItem
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * @return int
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param int $color
     *
     * @return FeedItem
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getFeedName()
    {
        return $this->feedName;
    }

    /**
     * @param string $feedName
     *
     * @return FeedItem
     */
    public function setFeedName($feedName)
    {
        $this->feedName = $feedName;
        return $this;
    }
}
