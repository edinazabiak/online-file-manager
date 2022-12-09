<?php

class ProfileController extends ProfileModel {

    public function getDatasAboutUser(string $username) : array
    {
        return $this->getUserByUsername($username)[0];
    }

    public function getNumbersAboutUser(string $username) : array
    {
        $result["storage"] = $this->getUserByUsername($username)[0]["Storage"] / 1000000000;
        $result["number"] = $this->getFilesNumberByUsername($username)[0]["number"];

        return $result;
    }

    public function modifyName(string $username, string $name) : void
    {
        $this->updateNameByUsername($username, $name);
    }

    public function modifyPassword(string $username, string $password) : void
    {
        $this->updatePasswordByUsername($username, $password);
    }
}