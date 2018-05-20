<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;

/**
 * Class UserService
 * @package Service
 */
class UserService
{
    /**
     * @var Connection
     */
    protected $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * @param $username
     * @param $password
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function createUser($username, $password)
    {
        return $this->database->insert('users', [
            'username' => $username,
            'password' => $this->createPassword($password)
        ]);
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function hasSignedIn()
    {
        if (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['userid'])) {
            return $this->checkCredentials($_SESSION['username'], $_SESSION['password']);
        }
        return false;
    }

    /**
     * @param array $input
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function signIn(Array $input)
    {
        if (!isset($input['username']) || !isset($input['password']) === 0) {
            return false;
        }

        return $this->checkCredentials($input['username'], $input['password']);
    }

    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public function checkPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    private function checkCredentials($username, $password)
    {
        if ($user = $this->database->fetchAssoc("SELECT * FROM `users` WHERE `username`=? LIMIT 1", [$username])) {
            if ($this->checkPassword($password, $user['password'])) {
                $_SESSION['userid'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                return true;
            }
        }
        return false;
    }

    /**
     * @param $password
     * @return bool|string
     */
    private function createPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
