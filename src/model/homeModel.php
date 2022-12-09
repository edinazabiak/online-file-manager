<?php

class HomeModel extends Database
{

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }

    public function getFilesByUsernameOrderByName(string $username) : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY name";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
        return $stmt->fetchAll();
    }

    public function getFilesByUsernameOrderByModifyAt(string $username) : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY modify_at DESC";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
        return $stmt->fetchAll();
    }

    public function getFilesByUsernameAndNameOrderByName(string $username, string $name) : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ? AND name LIKE '%' ? '%' ORDER BY name";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username, $name]);
        
        return $stmt->fetchAll();
    }

    public function getFilesByUsernameAndNameOrderByModifyAt(string $username, string $name) : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ? AND name LIKE '%' ? '%' ORDER BY modify_at DESC";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username, $name]);
        
        return $stmt->fetchAll();
    }

    public function getUserByUsername(string $username) : array
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
        return $stmt->fetchAll();
    }

    public function getFileByUsernameAndId(string $id, string $username) : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ? and id = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username, $id]);
        
        return $stmt->fetchAll();
    }

    public function updateStorageByUsername(int $size, string $username) : void
    {
        $sql = "UPDATE user SET storage=? WHERE username=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $size,
            $username 
        ]);
    }

    public function deleteFileById(string $id) : void
    {
        $sql = "DELETE FROM files WHERE id=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$id]);
    }

    public function deleteFileFromFolder(string $file_name, string $username)
    {
        $path = "./assets/files/" . $username . "/". basename($file_name);
        try {
            unlink($path);
        } catch(Exception $e) {
            die("A fájl feltöltése nem sikerült!" . $e->getMessage());
        }
    }

    public function manageDeleteFunction(string $id, string $username) : void
    {
        //var_dump($this->getFileByUsernameAndId($id, $username));
        //echo $id . " - " . $username;
        $file = $this->getFileByUsernameAndId($id, $username)[0];
        $user = $this->getUserByUsername($username)[0];
        $size = intval($user["Storage"]) + intval($file["size"]);

        $this->updateStorageByUsername($size, $username);
        $this->deleteFileById($id);
        $this->deleteFileFromFolder($file["original_name"], $username);
    }

}