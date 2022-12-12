<?php

class SendModel extends Database {

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }
    
    public function getFilesByUsernameOrderByName(?array $limit = null) : array
    {
        if ($limit == null) {
            $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY name";
            $stmt = $this->connectDatabase()->prepare($sql);
            $stmt->execute([$_SESSION["username"]]);
        
            return $stmt->fetchAll();
        } else {
            $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY name LIMIT " . $limit[0] . "," . $limit[1] ."";
            $stmt = $this->connectDatabase()->prepare($sql);
            $stmt->execute([$_SESSION["username"]]);
            
            return $stmt->fetchAll();
        }
    }

    public function getFilesByUsernameAndNameOrderByName(string $name, ?array $limit = null) : array
    {
        if ($limit == null) {
            $sql = "SELECT * FROM files WHERE user_id = ? AND name LIKE '%' ? '%' ORDER BY name";
            $stmt = $this->connectDatabase()->prepare($sql);
            $stmt->execute([$_SESSION["username"], $name]);
        
            return $stmt->fetchAll();
        } else {
            $sql = "SELECT * FROM files WHERE user_id = ? AND name LIKE '%' ? '%' ORDER BY name LIMIT " . $limit[0] . "," . $limit[1] ."";
            $stmt = $this->connectDatabase()->prepare($sql);
            $stmt->execute([$_SESSION["username"], $name]);
        
            return $stmt->fetchAll();
        }
    }

    public function getUserByUsername(string $username) : array
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
        return $stmt->fetchAll();
    }

    public function getSizeOfFileById(string $id)
    {
        $sql = "SELECT * FROM files WHERE user_id = ? and id = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$_SESSION["username"], $id]);
        
        return $stmt->fetchAll();
    }

    public function updateStorage(string $username, int $size) : void
    {
        $sql = "UPDATE user SET storage=? WHERE username=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $size,
            $username 
        ]);
    }

    public function insertFile(string $filename, string $original_name, int $size, string $type, string $username) : void
    {
        $sql = "INSERT INTO files (name, original_name, size, type, user_id, original_user_id) VALUES (?,?,?,?,?,?)";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $filename, 
            $original_name, 
            $size, 
            $type, 
            $username,
            $_SESSION["username"]
        ]);
    }

    public function copyFile(string $username, string $name) : void
    {
        $path = "./assets/files/" . $username;
        if (!is_dir($path)) {
            mkdir($path);
        }
        $from = "./assets/files/" . $_SESSION["username"] . "/". basename($name);
        $to = "./assets/files/" . $username . "/". basename($name);
        copy($from, $to);
    }

    public function validateStorage(string $username, array $files)
    {
        $user = $this->getUserByUsername($username);
        $total_size = 0;
        foreach($files as $file) {
            $total_size += $this->getSizeOfFileById($file)[0]["size"];
        }

        if ($user[0]["Storage"] >= $total_size) {
            $this->updateStorage($username, $user[0]["Storage"] - $total_size);
            foreach($files as $file) {
                $result = $this->getSizeOfFileById($file)[0];
                $this->insertFile($result["name"], $result["original_name"], $result["size"], $result["type"], $username);
                $this->copyFile($username, $result["original_name"]);
            }
            return true;
        }
        return false;
    }
}