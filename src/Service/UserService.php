<?php

namespace Service;

/**
 * Class UserService
 * @package Service
 */
class UserService
{
    public function createUser($username, $password)
    {

    }

    /**
     * @return bool
     */
    public function hasSignedIn()
    {
        if (isset($_SESSION['test']) && $_SESSION['test']) {
            return true;
        }

       return false;
    }

    /**
     * @param Array $input
     * @return bool
     */
    public function signIn(Array $input)
    {
        if (!isset($input['username']) || strlen($input['username']) === 0) {
            return false;
        }
        if (!isset($input['password']) || strlen($input['password']) === 0) {
            return false;
        }

        $_SESSION['test'] = true;
        return true;
    }
}
