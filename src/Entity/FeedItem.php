<?php

namespace Entity;

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
     * @var string
     */
    protected $site;

    /**
     * @var boolean
     */
    protected $pinned = false;

    /**
     * @param $id
     * @param $title
     * @param $description
     * @param $url
     * @param $site
     */
    public function __construct($id, $title, $description, $url, $site)
    {
        $this->id          = $id;
        $this->title       = $title;
        $this->description = $description;
        $this->url         = $url;
        $this->dateAdded   = new \DateTime;
        $this->site        = $site;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return preg_replace( "/\r|\n/", "", $this->description);
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return boolean
     */
    public function isViewed()
    {
        return $this->viewed;
    }

    /**
     * @param boolean $viewed
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param string $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return boolean
     */
    public function isPinned()
    {
        return $this->pinned;
    }

    /**
     * @param boolean $pinned
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;
    }
}
