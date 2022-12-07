<?php

class HomeModel extends Database
{

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }

    public function getFilesByUsername(string $username) : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY name";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
        return $stmt->fetchAll();
    }

}