<?php

class ProfileController extends ProfileModel {

    public function getDatasAboutUser() : array
    {
        return $this->getUserByUsername()[0];
    }

    public function getNumbersAboutUser() : array
    {
        $result["storage"] = $this->getUserByUsername()[0]["Storage"] / 1000000000;
        $result["number"] = $this->getFilesNumberByUsername()[0]["number"];

        return $result;
    }

    public function modifyName(string $name) : void
    {
        $this->updateNameByUsername($name);
    }

    public function modifyPassword(string $password) : void
    {
        $this->updatePasswordByUsername($password);
    }
}