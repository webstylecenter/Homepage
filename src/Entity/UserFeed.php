<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 */
class UserFeed
{
    use  TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userFeeds")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Feed
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Feed")
     * @ORM\JoinColumn(name="feed_id", referencedColumnName="id")
     */
    protected $feed;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $icon = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $color = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $autoPin = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFeedItem", mappedBy="userFeed", cascade={"persist", "remove"})
     * @var ArrayCollection
     */
    protected $items = [];


    public function __construct()
    {
        $this->items = new ArrayCollection;
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
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon)
    {
        $this->icon = $icon;
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
    public function setColor(string $color)
    {
        $this->color = $color;
    }

    /**
     * @return bool
     */
    public function isAutoPin()
    {
        return $this->autoPin;
    }

    /**
     * @param bool $autoPin
     */
    public function setAutoPin(bool $autoPin)
    {
        $this->autoPin = $autoPin;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems(ArrayCollection $items)
    {
        $this->items = $items;
    }
}
