<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feed", mappedBy="user", orphanRemoval=true)
     */
    private $feeds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChecklistItem", mappedBy="user", orphanRemoval=true)
     */
    private $checklistItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="user", orphanRemoval=true)
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedItem", mappedBy="user", orphanRemoval=true)
     */
    private $feedItems;

    public function __construct()
    {
        parent::__construct();
        $this->feeds = new ArrayCollection();
        $this->checklistItems = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->feedItems = new ArrayCollection();
        // your own logic
    }

    /**
     * @return Collection|Feed[]
     */
    public function getFeeds(): Collection
    {
        return $this->feeds;
    }

    public function addFeed(Feed $feed): self
    {
        if (!$this->feeds->contains($feed)) {
            $this->feeds[] = $feed;
            $feed->setUser($this);
        }

        return $this;
    }

    public function removeFeed(Feed $feed): self
    {
        if ($this->feeds->contains($feed)) {
            $this->feeds->removeElement($feed);
            // set the owning side to null (unless already changed)
            if ($feed->getUser() === $this) {
                $feed->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ChecklistItem[]
     */
    public function getChecklistItems(): Collection
    {
        return $this->checklistItems;
    }

    public function addChecklistItem(ChecklistItem $checklistItem): self
    {
        if (!$this->checklistItems->contains($checklistItem)) {
            $this->checklistItems[] = $checklistItem;
            $checklistItem->setUser($this);
        }

        return $this;
    }

    public function removeChecklistItem(ChecklistItem $checklistItem): self
    {
        if ($this->checklistItems->contains($checklistItem)) {
            $this->checklistItems->removeElement($checklistItem);
            // set the owning side to null (unless already changed)
            if ($checklistItem->getUser() === $this) {
                $checklistItem->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setUser($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getUser() === $this) {
                $note->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FeedItem[]
     */
    public function getFeedItems(): Collection
    {
        return $this->feedItems;
    }

    public function addFeedItem(FeedItem $feedItem): self
    {
        if (!$this->feedItems->contains($feedItem)) {
            $this->feedItems[] = $feedItem;
            $feedItem->setUser($this);
        }

        return $this;
    }

    public function removeFeedItem(FeedItem $feedItem): self
    {
        if ($this->feedItems->contains($feedItem)) {
            $this->feedItems->removeElement($feedItem);
            // set the owning side to null (unless already changed)
            if ($feedItem->getUser() === $this) {
                $feedItem->setUser(null);
            }
        }

        return $this;
    }
}