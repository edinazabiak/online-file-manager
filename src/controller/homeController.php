<?php

class HomeController extends HomeModel {

    private int $files_per_page = 20;

    public function __construct() { }
    
    public function listOfFiles(string $order, int $page, ?string $filter = null) : array
    {
        $start_from = ($page - 1) * $this->files_per_page;
        $limit_array = [$start_from, $this->files_per_page];

        if ($order == "1") {
            if ($filter == null) {
                return $this->getFilesByUsernameOrderByName($limit_array);
            } else {
                return $this->getFilesByUsernameAndNameOrderByName($filter, $limit_array);
            }
        }
        else {
            if ($filter == null) {
                return $this->getFilesByUsernameOrderByModifyAt($limit_array);
            } else {
                return $this->getFilesByUsernameAndNameOrderByModifyAt($filter, $limit_array);
            }
        }
    }

    public function countOfPages(string $order, ?string $filter = null) : string
    {
        if ($order == "1") {
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
        else {
            if ($filter == null) {
                $total_records = count($this->getFilesByUsernameOrderByModifyAt());
                $total_pages = ceil($total_records / $this->files_per_page);
                return $total_pages;
            } else {
                $total_records = count($this->getFilesByUsernameAndNameOrderByModifyAt($filter));
                $total_pages = ceil($total_records / $this->files_per_page);
                return $total_pages;
            }
        }
    }

    public function chooseFileToDelete(string $file_id) : void
    {
        $this->manageDeleteFunction($file_id);
    }

    public function chooseFileToDownload(string $id) : array
    {
        return $this->getFileByUsernameAndId($id);
    }
}