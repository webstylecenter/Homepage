<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedSettingRepository")
 */
class FeedSetting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="feedSettings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Feed", inversedBy="feedSettings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feed;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $feedIcon;

    /**
     * @ORM\Column(type="boolean")
     */
    private $autoPin;

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

    public function getFeed(): ?Feed
    {
        return $this->feed;
    }

    public function setFeed(?Feed $feed): self
    {
        $this->feed = $feed;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getFeedIcon(): ?string
    {
        return $this->feedIcon;
    }

    public function setFeedIcon(?string $feedIcon): self
    {
        $this->feedIcon = $feedIcon;

        return $this;
    }

    public function getAutoPin(): ?bool
    {
        return $this->autoPin;
    }

    public function setAutoPin(bool $autoPin): self
    {
        $this->autoPin = $autoPin;

        return $this;
    }
}
