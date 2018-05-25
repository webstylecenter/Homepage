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
     * @ORM\OneToMany(targetEntity="App\Entity\ChecklistItem", mappedBy="user", orphanRemoval=true)
     */
    private $checklistItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="user", orphanRemoval=true)
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedSetting", mappedBy="user", orphanRemoval=true)
     */
    private $feedSettings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedItemStatus", mappedBy="user", orphanRemoval=true)
     */
    private $feedItemStatuses;

    public function __construct()
    {
        parent::__construct();
        $this->feeds = new ArrayCollection();
        $this->checklistItems = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->feedSettings = new ArrayCollection();
        $this->feedItemStatuses = new ArrayCollection();
        // your own logic
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
            $feedSetting->setUser($this);
        }

        return $this;
    }

    public function removeFeedSetting(FeedSetting $feedSetting): self
    {
        if ($this->feedSettings->contains($feedSetting)) {
            $this->feedSettings->removeElement($feedSetting);
            // set the owning side to null (unless already changed)
            if ($feedSetting->getUser() === $this) {
                $feedSetting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FeedItemStatus[]
     */
    public function getFeedItemStatuses(): Collection
    {
        return $this->feedItemStatuses;
    }

    public function addFeedItemStatus(FeedItemStatus $feedItemStatus): self
    {
        if (!$this->feedItemStatuses->contains($feedItemStatus)) {
            $this->feedItemStatuses[] = $feedItemStatus;
            $feedItemStatus->setUser($this);
        }

        return $this;
    }

    public function removeFeedItemStatus(FeedItemStatus $feedItemStatus): self
    {
        if ($this->feedItemStatuses->contains($feedItemStatus)) {
            $this->feedItemStatuses->removeElement($feedItemStatus);
            // set the owning side to null (unless already changed)
            if ($feedItemStatus->getUser() === $this) {
                $feedItemStatus->setUser(null);
            }
        }

        return $this;
    }
}