<?php

class ModifyController extends ModifyModel {

    public function validUserAndFile(string $hash_id) : bool
    {
        $files = $this->getFilesByUsername();

        foreach($files as $f) {
            if (hash("md2", $f["id"]) == $hash_id) {
                return true;
            }
        }

        return false;
    }

    public function getDatasOfFile(string $hash_id) : array
    {
        $files = $this->getFilesByUsername();

        foreach($files as $f) {
            if (hash("md2", $f["id"]) == $hash_id) {
                $path = "./assets/files/" . $_SESSION["username"] . "/". basename($f["name"]);
                $file_info = pathinfo($path);
                return [$f["id"], $file_info["filename"], $file_info["extension"]];
            }
        }
    }

    public function getContentOfFile(string $name) : string
    {
        $path = "./assets/files/" . $_SESSION["username"] . "/". basename($name);
        return file_get_contents($path);
    }

    public function modifyName(string $id, string $name) : void
    {
        $this->updateModifyAt($id);
        $this->updateName($id, $name);
    }

    public function modifyFile(string $id, string $name, string $content) : void
    {
        $this->updateModifyAt($id);
        $this->updateFile($_SESSION["username"], $name, $content);
    }
}