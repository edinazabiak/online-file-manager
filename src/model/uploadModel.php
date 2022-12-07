<?php


class UploadModel extends Database {

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host);
        parent::__construct($dbname);
        parent::__construct($user);
        parent::__construct($password);
    }

    public function uploadFile(string $name, string $original_name, int $size, string $type, string $tmp) : bool
    {
        $path = "./assets/files/" . $_SESSION["username"];
        if (!is_dir($path)) {
            mkdir($path);
        }

        try {
            move_uploaded_file($tmp, $path . "/". basename($name));
        } catch(Exception $e) {
            return false;
        }

        $this->insertFile($name, $original_name, $size, $type);
        return true;
    }

    public function insertFile(string $name, string $original_name, int $size, string $type) : void
    {
        $sql = "INSERT INTO files (name, original_name, size, type, user_id) VALUES (?,?,?,?,?)";
        $stmt = $this->connectDatabase()->prepare($sql);
        $stmt->execute([
            $name, 
            $original_name, 
            $size, 
            $type, 
            $_SESSION["username"]
        ]);
    }

}