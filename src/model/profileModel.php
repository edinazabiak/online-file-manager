<?php

class ProfileModel extends Database {

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }

    public function getUserByUsername() : array
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$_SESSION["username"]]);
        
        return $stmt->fetchAll();
    }

    public function getFilesNumberByUsername() : array
    {
        $sql = "SELECT count(*) number FROM files WHERE user_id = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$_SESSION["username"]]);
        
        return $stmt->fetchAll();
    }

    public function updateNameByUsername(string $name) : void
    {
        $sql = "UPDATE user SET name=? WHERE username=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $name,
            $_SESSION["username"]
        ]);
    }

    public function updatePasswordByUsername(string $password) : void
    {
        $sql = "UPDATE user SET password=? WHERE username=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $password,
            $_SESSION["username"]
        ]);
    }
}