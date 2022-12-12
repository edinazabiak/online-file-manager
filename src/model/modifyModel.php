<?php

class ModifyModel extends Database {

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }

    public function getFilesByUsername() : array
    {
        $sql = "SELECT * FROM files WHERE user_id = ?";
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

    public function updateName(string $id, string $name) : void
    {
        $sql = "UPDATE files SET name=? WHERE id=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $name,
            $id 
        ]);
    }

    public function updateModifyAt(string $id) : void
    {
        $sql = "UPDATE files SET modify_at=? WHERE id=?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            date("Y-m-d H:i:s"),
            $id 
        ]);
    }

    public function updateFile(string $name, string $content) : void
    {
        $path = "./assets/files/" . $_SESSION["username"] . "/". basename($name) . ".txt";
        $file = fopen($path, "w") or die("A fájl megnyitása nem sikerült!");
        fwrite($file, $content);
        fclose($file);

        // $file_info = pathinfo($path);

        // $name = $file_info["basename"];
        // $type = $file_info["extension"];
        // $size = strval(filesize($path));

        // $storage = $this->getStorageByUsername($username)[0]["storage"];
        // if ($storage < $size) {
        //     unlink($file);
        //     return false;
        // }

        // $new_size = $storage - $size;
        // $this->updateStorage($username, $new_size);
        // $this->insertFile($name, $name, $size, $type);
        
        // return true;
    }
}