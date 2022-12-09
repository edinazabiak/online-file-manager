<?php

class ProfileModel extends Database {

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

    public function getFilesNumberByUsername(string $username) : array
    {
        $sql = "SELECT count(*) number FROM files WHERE user_id = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
        return $stmt->fetchAll();
    }

    public function updateNameByUsername(string $username, string $name) : void
    {
        $sql = "UPDATE user SET name=? WHERE username=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $name,
            $username
        ]);
    }

    public function updatePasswordByUsername(string $username, string $password) : void
    {
        $sql = "UPDATE user SET password=? WHERE username=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $password,
            $username
        ]);
    }
}