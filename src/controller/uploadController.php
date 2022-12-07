<?php

class UploadController extends uploadModel {

    public function manageFiles(string $name, string $original_name, int $size, string $type, string $tmp) : bool
    {
        $extension = explode(".", $original_name)[1];
        $new_name = $name . "." . $extension;
        return $this->uploadFile($new_name, $original_name, $size, $type, $tmp);
    }
}