<?php


class UploadModel extends Database {

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }

    public function createFile(string $username, string $name, string $content) : bool
    {
        $path = "./assets/files/" . $username . "/". basename($name) . ".txt";
        $file = fopen($path, "w") or die("A fájl létrehozása nem sikerült!");
        fwrite($file, $content);
        fclose($file);

        $file_info = pathinfo($path);

        $name = $file_info["basename"];
        $type = $file_info["extension"];
        $size = strval(filesize($path));

        $storage = $this->getStorageByUsername($username)[0]["storage"];
        if ($storage < $size) {
            unlink($file);
            return false;
        }

        $new_size = $storage - $size;
        $this->updateStorage($username, $new_size);
        $this->insertFile($name, $name, $size, $type);
        
        return true;
    }

    public function uploadFile(string $username, string $filename, string $original_name, int $size, string $type, string $tmp) : bool
    {
        $storage = $this->getStorageByUsername($username)[0]["storage"];
        if ($storage < $size) {
            return false;
        }

        $path = "./assets/files/" . $username;
        if (!is_dir($path)) {
            mkdir($path);
        }

        try {
            move_uploaded_file($tmp, $path . "/". basename($original_name));
        } catch(Exception $e) {
            die("A fájl feltöltése nem sikerült!" . $e->getMessage());
        }

        $new_size = $storage - $size;
        $this->updateStorage($username, $new_size);
        $this->insertFile($filename, $original_name, $size, $type);
        return true;
    }

    public function getStorageByUsername(string $username) : array
    {
        $sql = "SELECT storage FROM user WHERE username = ?";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([$username]);
        
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

    public function insertFile(string $filename, string $original_name, int $size, string $type) : void
    {
        $sql = "INSERT INTO files (name, original_name, size, type, user_id, original_user_id) VALUES (?,?,?,?,?,?)";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $filename, 
            $original_name, 
            $size, 
            $type, 
            $_SESSION["username"],
            $_SESSION["username"]
        ]);
    }

}