<?php

class LoginModel extends Database
{

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }

    public function getUserByUsername(string $username) : array
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
        return $stmt->fetchAll();
    }

    public function getUserByEmail(string $email) : array
    {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$email]);
        
        return $stmt->fetchAll();
    }

    public function insertUser(string $name, string $username, string $email, string $password)
    {
        $sql = "INSERT INTO user (username, email, name, password) VALUES (?,?,?,?)";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $username, 
            $email, 
            $name, 
            $password
        ]);
    }

    public function verifyUsername(string $username) : bool
    {
        if (count($this->getUserByUsername($username)) == 0) {
            return true;
        }
        return false;
    }

    public function verifyEmail(string $email) : bool
    {
        if (count($this->getUserByEmail($email)) == 0) {
            return 1;
        }
        return false;
    }

    public function verifyUserByUsername(string $username, string $password) : bool
    {
        if (count($this->getUserByUsername($username)) == 0 || $this->getUserByUsername($username)[0]["Password"] != hash("sha256", $password)) {
            return false;
        }
        return true;
    }

}