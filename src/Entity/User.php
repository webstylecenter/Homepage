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
     * @ORM\OneToMany(targetEntity="App\Entity\UserFeed", mappedBy="user", cascade={"persist", "remove"})
     * @var UserFeed[]
     */
    protected $userFeeds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChecklistItem", mappedBy="user", cascade={"persist", "remove"})
     * @var ChecklistItem[]
     */
    protected $checklistItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="user", cascade={"persist", "remove"})
     * @var Note[]
     */
    protected $notes;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $hideXFrameNotice = false;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $ipAddress;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $userAgent;

    /**
     * @ORM\OneToMany(targetEntity="UserSetting", mappedBy="user", cascade={"persist", "remove"})
     * @var UserSetting[]
     */
    protected $userSettings;

    public function __construct()
    {
        parent::__construct();

        $this->ipAddress = $_SERVER['X-Forwarded-For'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    /**
     * @return ChecklistItem[]
     */
    public function getChecklistItems()
    {
        return $this->checklistItems;
    }

    /**
     * @param ChecklistItem[] $checklistItems
     */
    public function setChecklistItems(array $checklistItems)
    {
        $this->checklistItems = $checklistItems;
    }

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
     * @return UserFeed[]
     */
    public function getUserFeeds()
    {
        return $this->userFeeds;
    }

    /**
     * @param UserFeed[] $userFeeds
     */
    public function setUserFeeds(array $userFeeds)
    {
        $this->userFeeds = $userFeeds;
    }

    /**
     * @return Note[]
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param Note[] $notes
     */
    public function setNotes(array $notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return bool
     */
    public function isHideXFrameNotice()
    {
        return $this->hideXFrameNotice;
    }

    /**
     * @param bool $hideXFrameNotice
     */
    public function setHideXFrameNotice(bool $hideXFrameNotice)
    {
        $this->hideXFrameNotice = $hideXFrameNotice;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return UserSetting[]
     */
    public function getUserSettings()
    {
        return $this->userSettings;
    }

    /**
     * @param UserSetting[] $userSettings
     * @return User
     */
    public function setUserSettings(array $userSettings)
    {
        $this->userSettings = $userSettings;
        return $this;
    }
}
