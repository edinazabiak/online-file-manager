<?php

class LoginController extends LoginModel {

    public function __construct() {}

    public function existsUsername(string $username, string $password) : bool
    {
        return $this->verifyUserByUsername($username, $password);
    }

    public function reservedUsername(string $username) : bool
    {
        return $this->verifyUsername($username);
    }

    public function reservedEmail(string $email) : bool
    {
        return $this->verifyEmail($email);
    }

    public function collectDatas(string $name, string $username, string $email, string $password)
    {
        $this->insertUser($name, $username, $email, $password);
    }

    public function getUser(string $username) : array
    {
        return $this->getUserByUsername($username);
    }

}