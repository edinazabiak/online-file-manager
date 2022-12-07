<?php

class Database {

    private string $host = "localhost";
    private string $dbname = "filemanager";
    private string $user = "root";
    private string $password = "";

    public function __construct() {}
    
    public function connectDatabase()
    {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->dbname";
            $conn = new PDO($dsn, $this->user, $this->password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            return $conn; 
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "code" => $e->getCode(),
                "message" => $e->getMessage(), 
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ]);
        }
    }
}