<?php

class ModifyController extends ModifyModel {

    public function validUserAndFile(string $hash_id, string $username) : bool
    {
        $files = $this->getFilesByUsername($username);

        foreach($files as $f) {
            if (hash("md2", $f["id"]) == $hash_id) {
                return true;
            }
        }

        return false;
    }

    public function getDatasOfFile(string $hash_id, string $username) : array
    {
        $files = $this->getFilesByUsername($username);

        foreach($files as $f) {
            if (hash("md2", $f["id"]) == $hash_id) {
                $path = "./assets/files/" . $username . "/". basename($f["name"]);
                $file_info = pathinfo($path);
                return [$f["id"], $file_info["filename"], $file_info["extension"]];
            }
        }
    }

    public function getContentOfFile(string $name, string $username) : string
    {
        $path = "./assets/files/" . $username . "/". basename($name);
        return file_get_contents($path);
    }

    public function modifyName(string $id, string $name) : void
    {
        $this->updateName($id, $name);
    }

    public function modifyFile(string $username, string $name, string $content) : void
    {
        $this->updateFile($username, $name, $content);
    }
}