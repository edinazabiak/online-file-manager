<?php

class HomeController extends HomeModel {
    
    public function listOfFiles(string $username) : array
    {
        return $this->getFilesByUsername($username);
    }
}