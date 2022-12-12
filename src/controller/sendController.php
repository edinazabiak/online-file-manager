<?php

class SendController extends SendModel {

    private int $files_per_page = 30;

    public function __construct() { }
    
    public function listOfFiles(int $page, ?string $filter = null) : array
    {
        $start_from = ($page - 1) * $this->files_per_page;
        $limit_array = [$start_from, $this->files_per_page];

        if ($filter == null) {
            return $this->getFilesByUsernameOrderByName($limit_array);
        } else {
            return $this->getFilesByUsernameAndNameOrderByName($filter, $limit_array);
        }
    }

    public function countOfPages(?string $filter = null) : string
    {
        if ($filter == null) {
            $total_records = count($this->getFilesByUsernameOrderByName());
            $total_pages = ceil($total_records / $this->files_per_page);
            return $total_pages;
        } else {
            $total_records = count($this->getFilesByUsernameAndNameOrderByName($filter));
            $total_pages = ceil($total_records / $this->files_per_page);
            return $total_pages;
        }
    }

    public function existsUser(string $username) : bool
    {
        if (count($this->getUserByUsername($username)) == 1) {
            return true;
        }
        return false;
    }

    public function isThereEnoughStorage(string $username, array $files) : bool
    {
        return $this->validateStorage($username, $files);
    }
    
}