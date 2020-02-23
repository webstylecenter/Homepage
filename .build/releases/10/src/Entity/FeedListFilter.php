<?php

namespace App\Entity;

class FeedListFilter
{
    const FEEDLIST_MAX_ITEMS = 50;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var integer
     */
    protected $limit = self::FEEDLIST_MAX_ITEMS;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var string
     */
    protected $searchQuery;

    /**
     * @var boolean
     */
    protected $newOnly = false;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return FeedListFilter
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return FeedListFilter
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return FeedListFilter
     */
    public function setPage(int $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    /**
     * @param string $searchQuery
     * @return FeedListFilter
     */
    public function setSearchQuery(string $searchQuery)
    {
        $this->searchQuery = $searchQuery;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNewOnly()
    {
        return $this->newOnly;
    }

    /**
     * @param bool $newOnly
     * @return FeedListFilter
     */
    public function setNewOnly(bool $newOnly)
    {
        $this->newOnly = $newOnly;
        return $this;
    }

    public function getIndex()
    {
        return ($this->page < 1 ? 1 : $this->page) - 1;
    }
}