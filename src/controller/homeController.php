<?php

class HomeController extends HomeModel {
    
    public function listOfFiles(string $username, string $order, ?string $filter = null) : array
    {
        if ($order == "1") {
            if ($filter == null) {
                return $this->getFilesByUsernameOrderByName($username);
            } else {
                return $this->getFilesByUsernameAndNameOrderByName($username, $filter);
            }
        }
        else {
            if ($filter == null) {
                return $this->getFilesByUsernameOrderByModifyAt($username);
            } else {
                return $this->getFilesByUsernameAndNameOrderByModifyAt($username, $filter);
            }
        }
    }

    public function chooseFileToDelete(string $file_id, string $username) : void
    {
        $this->manageDeleteFunction($file_id, $username);
    }

    public function chooseFileToDownload(string $id, string $username) : array
    {
        return $this->getFileByUsernameAndId($id, $username);
    }
}