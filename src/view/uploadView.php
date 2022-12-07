<?php

class UploadView extends uploadController {

    public function __construct() {}

    public function showForm()
    {
        if (isset($_POST["back"])) {
            header("Location: .");
        } else if (isset($_POST["upload"])) {
            $file_name = $_POST["name"];
            $file_original_name = $_FILES["file"]["name"];
            $file_size = $_FILES["file"]["size"];
            $file_type = $_FILES["file"]["type"];
            $tmp_name = $_FILES["file"]["tmp_name"];
            $result = $this->manageFiles($file_name, $file_original_name, $file_size, $file_type, $tmp_name);
            if ($result == true) {
                header("Location: .");
            }
            else {
                echo "Hiba történt a fájl feltöltésekor!";
            }
        }

        $page = new PageElement();
        $page->head(true);
        $page->header($_SESSION["username"]);

        echo "<section class='functions-section'>
            <form method='POST' enctype='multipart/form-data'>
                <input type='file' id='file' name='file'>
                <input type='text' id='name' name='name' placeholder='Fájl neve'>

                <div>
                    <input class='btn btn-primary' type='submit' name='upload' value='Feltöltés'>
                    <input class='btn btn-light' type='submit' name='back' value='Vissza'>
                </div>
            </form>
        </section>";

        $page->footer();
    }

}