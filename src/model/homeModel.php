<?php

class HomeModel extends Database
{

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

    public function getFilesByUsernameOrderByModifyAt(?array $limit = null) : array
    {
        if ($limit == null) {
            $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY modify_at DESC";
            $stmt = $this->connectDatabase()->prepare($sql);
            $stmt->execute([$_SESSION["username"]]);
        
            return $stmt->fetchAll();
        } else {
            $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY modify_at DESC LIMIT " . $limit[0] . "," . $limit[1] ."";
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

    public function getFilesByUsernameAndNameOrderByModifyAt(string $name, ?array $limit = null) : array
    {
        if ($limit == null) {
            $sql = "SELECT * FROM files WHERE user_id = ? AND name LIKE '%' ? '%' ORDER BY modify_at DESC";
            $stmt = $this->connectDatabase()->prepare($sql);
            $stmt->execute([$_SESSION["username"], $name]);
            
            return $stmt->fetchAll();
        } else {
            $sql = "SELECT * FROM files WHERE user_id = ? AND name LIKE '%' ? '%' ORDER BY modify_at DESC LIMIT " . $limit[0] . "," . $limit[1] ."";
            $stmt = $this->connectDatabase()->prepare($sql);
            $stmt->execute([$_SESSION["username"], $name]);
            
            return $stmt->fetchAll();
        }
    }

    public function getUserByUsername() : array
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$_SESSION["username"]]);
        
        return $stmt->fetchAll();
    }

    public function getFileByUsernameAndId(string $id) : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ? and id = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$_SESSION["username"], $id]);
        
        return $stmt->fetchAll();
    }

    public function updateStorageByUsername(int $size) : void
    {
        $sql = "UPDATE user SET storage=? WHERE username=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $size,
            $_SESSION["username"] 
        ]);
    }

    public function deleteFileById(string $id) : void
    {
        $sql = "DELETE FROM files WHERE id=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$id]);
    }

    public function deleteFileFromFolder(string $file_name)
    {
        $path = "./assets/files/" . $_SESSION["username"] . "/". basename($file_name);
        try {
            unlink($path);
        } catch(Exception $e) {
            die("A fájl feltöltése nem sikerült!" . $e->getMessage());
        }
    }

    public function manageDeleteFunction(string $id) : void
    {
        //var_dump($this->getFileByUsernameAndId($id, $username));
        //echo $id . " - " . $username;
        $file = $this->getFileByUsernameAndId($id, $_SESSION["username"])[0];
        $user = $this->getUserByUsername($_SESSION["username"])[0];
        $size = intval($user["Storage"]) + intval($file["size"]);

        $this->updateStorageByUsername($size, $_SESSION["username"]);
        $this->deleteFileById($id);
        $this->deleteFileFromFolder($file["original_name"], $_SESSION["username"]);
    }

}