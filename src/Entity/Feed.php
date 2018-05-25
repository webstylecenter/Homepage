<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedRepository")
 */
class Feed
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $feedUrl;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedItem", mappedBy="feed", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedSetting", mappedBy="feed", orphanRemoval=true, fetch="EAGER")
     */
    private $feedSettings;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->feedSettings = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFeedUrl(): ?string
    {
        return $this->feedUrl;
    }

    public function setFeedUrl(string $feedUrl): self
    {
        $this->feedUrl = $feedUrl;

        return $this;
    }

    /**
     * @return Collection|FeedItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItems(FeedItem $pinned): self
    {
        if (!$this->items->contains($pinned)) {
            $this->items[] = $pinned;
            $pinned->setFeed($this);
        }

        return $this;
    }

    public function removeItems(FeedItem $pinned): self
    {
        if ($this->items->contains($pinned)) {
            $this->items->removeElement($pinned);
            // set the owning side to null (unless already changed)
            if ($pinned->getFeed() === $this) {
                $pinned->setFeed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FeedSetting[]
     */
    public function getFeedSettings(): Collection
    {
        return $this->feedSettings;
    }

    public function addFeedSetting(FeedSetting $feedSetting): self
    {
        if (!$this->feedSettings->contains($feedSetting)) {
            $this->feedSettings[] = $feedSetting;
            $feedSetting->setFeed($this);
        }

        return $this;
    }

    public function removeFeedSetting(FeedSetting $feedSetting): self
    {
        if ($this->feedSettings->contains($feedSetting)) {
            $this->feedSettings->removeElement($feedSetting);
            // set the owning side to null (unless already changed)
            if ($feedSetting->getFeed() === $this) {
                $feedSetting->setFeed(null);
            }
        }

        return $this;
    }
}
