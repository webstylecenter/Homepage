<?php

namespace Entity;

/**
 * Class User
 * @package Entity
 */
class User
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var DateTime
     */
    protected $signupDate;

    /**
     * @var DateTime
     */
    protected $lastLogin;

    /**
     * Feed constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param $signupDate
     * @param null $lastLogin
     */
    public function __construct($id, $username, $password, $signupDate, $lastLogin = null)
    {
        $this->id            = $id;
        $this->username      = $username;
        $this->password      = $password;
        $this->signupDate    = $signupDate;
        $this->lastLogin     = ($lastLogin !== null ? $lastLogin : \DateTime);
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
     * @return Feed
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Feed
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Feed
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getSignupDate()
    {
        return $this->signupDate;
    }

    /**
     * @param DateTime $signupDate
     * @return Feed
     */
    public function setSignupDate($signupDate)
    {
        $this->signupDate = $signupDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     * @return Feed
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }
}
