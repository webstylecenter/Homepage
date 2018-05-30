<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedItemRepository")
 */
class FeedItem
{
    use  TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $guid;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected $description = null;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Feed", inversedBy="items")
     * @ORM\JoinColumn(name="feed_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Feed
     */
    protected $feed;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $pinned = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     */
    public function setGuid(string $guid)
    {
        $this->guid = $guid;
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
    public function setTitle(string $title)
    {
        $this->title = strlen($title) > 250 ? substr($title,0,250)."..." : $title;;
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
     */
    public function setDescription(string $description)
    {
        $this->description = strlen($description) > 250 ? substr($description,0,250)."..." : $description;
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
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param Feed $feed
     */
    public function setFeed(Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * @return bool
     */
    public function isPinned()
    {
        return $this->pinned;
    }

    /**
     * @param bool $pinned
     */
    public function setPinned(bool $pinned)
    {
        $this->pinned = $pinned;
    }
}
