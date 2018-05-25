<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedItemStatusRepository")
 */
class FeedItemStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="feedItemStatuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $viewed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pinned;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FeedItem", inversedBy="FeedItemStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feedItem;

    public function getId()
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getViewed(): ?bool
    {
        return $this->viewed;
    }

    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    public function getPinned(): ?bool
    {
        return $this->pinned;
    }

    public function setPinned(bool $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    public function getFeedItem(): ?FeedItem
    {
        return $this->feedItem;
    }

    public function setFeedItem(?FeedItem $feedItem): self
    {
        $this->feedItem = $feedItem;

        return $this;
    }
}
