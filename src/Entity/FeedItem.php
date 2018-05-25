<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Feed", inversedBy="items", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feed;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedItemStatus", mappedBy="feedItem", orphanRemoval=true, fetch="EAGER")
     */
    private $FeedItemStatus;

    public function __construct()
    {
        $this->FeedItemStatus = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getFeed(): ?Feed
    {
        return $this->feed;
    }

    public function setFeed(?Feed $feed): self
    {
        $this->feed = $feed;

        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * @return Collection|FeedItemStatus[]
     */
    public function getFeedItemStatus(): Collection
    {
        return $this->FeedItemStatus;
    }

    public function addFeedItemStatus(FeedItemStatus $feedItemStatus): self
    {
        if (!$this->FeedItemStatus->contains($feedItemStatus)) {
            $this->FeedItemStatus[] = $feedItemStatus;
            $feedItemStatus->setFeedItem($this);
        }

        return $this;
    }

    public function removeFeedItemStatus(FeedItemStatus $feedItemStatus): self
    {
        if ($this->FeedItemStatus->contains($feedItemStatus)) {
            $this->FeedItemStatus->removeElement($feedItemStatus);
            // set the owning side to null (unless already changed)
            if ($feedItemStatus->getFeedItem() === $this) {
                $feedItemStatus->setFeedItem(null);
            }
        }

        return $this;
    }
}
