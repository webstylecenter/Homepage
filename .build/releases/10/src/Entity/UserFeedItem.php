<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 */
class UserFeedItem
{
    use  TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\FeedItem", cascade={"persist"})
     * @ORM\JoinColumn(name="feed_item_id", referencedColumnName="id")
     *
     * @var FeedItem
     */
    protected $feedItem;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserFeed", inversedBy="items")
     * @ORM\JoinColumn(name="user_feed_id", referencedColumnName="id", nullable=true)
     *
     * @var UserFeed
     */
    protected $userFeed;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $viewed = false;

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
     * @return FeedItem
     */
    public function getFeedItem()
    {
        return $this->feedItem;
    }

    /**
     * @param FeedItem $feedItem
     */
    public function setFeedItem(FeedItem $feedItem)
    {
        $this->feedItem = $feedItem;
    }

    /**
     * @return UserFeed
     */
    public function getUserFeed()
    {
        return $this->userFeed;
    }

    /**
     * @param UserFeed $userFeed
     */
    public function setUserFeed(UserFeed $userFeed)
    {
        $this->userFeed = $userFeed;
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
     */
    public function setViewed(bool $viewed)
    {
        $this->viewed = $viewed;
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
