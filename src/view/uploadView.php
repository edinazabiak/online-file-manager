<?php

class UploadView extends uploadController {

    public function __construct(
        private array $errors = [
            "storage" => 1,
            "empty_file" => 1, 
            "empty_name" => 1
        ]
    ) {}

    public function showUploadErrors()
    {
        if ($this->errors["empty_file"] == 0) {
            echo "<input class='error' type='file' id='file' name='file'>";
        } else if ($this->errors["storage"] == 0) {
            echo "
            <p class='error-text'>Kevés a tárhely!</p>
            <input class='error' type='file' id='file' name='file'>";
        } else {
            echo "<input type='file' id='file' name='file'>";
        }

        if ($this->errors["empty_name"] == 0) {
            echo "<input class='error' type='text' id='name' name='name' placeholder='Kötelező megadni a fájl nevét!'>";
        } else {
            echo "<input type='text' id='name' name='name' placeholder='Fájl neve'>";
        }
    }

    public function showCreateErrors()
    {
        if ($this->errors["empty_name"] == 0) {
            echo "<input class='error' type='text' name='name' placeholder='Kötelező nevet adni a fájlnak!'>";
        } else {
            echo "<input type='text' name='name' placeholder='Fájl neve'>";
        }
    }

    public function showUploadForm()
    {
        if (isset($_POST["back"])) {
            header("Location: .");
        } else if (isset($_POST["upload"])) {
            if (empty($_POST["name"])) {
                $this->errors["empty_name"] = 0;
            }
            else {
                $this->errors["empty_name"] = 1;
                $file_name = $_POST["name"];
            }

            if (empty($_FILES["file"]["name"])) {
                $this->errors["empty_file"] = 0;
            } else {
                $this->errors["empty_file"] = 1;
            }

            if ($this->errors["empty_name"] == 1 && $this->errors["empty_file"] == 1) {
                $file_original_name = strtolower($_FILES["file"]["name"]);
                $file_size = $_FILES["file"]["size"];
                $file_type = $_FILES["file"]["type"];
                $tmp_name = $_FILES["file"]["tmp_name"];

                $result = $this->manageFiles($_SESSION["username"], $file_name, $file_original_name, $file_size, $file_type, $tmp_name);

                if ($result) {
                    $this->errors["storage"] = 1;
                    header("Location: .");
                } else {
                    $this->errors["storage"] = 0;
                }
            }
        }

        $page = new PageElement();
        $page->head(true);
        $page->header($_SESSION["name"]);

        echo "<section>
            <h2>Fájl feltöltése</h2>
            <form method='POST' enctype='multipart/form-data'>";
        $this->showUploadErrors();
        echo "  <div>
                    <input class='btn btn-primary' type='submit' name='upload' value='Feltöltés'>
                    <input class='btn btn-light' type='submit' name='back' value='Vissza'>
                </div>
            </form>
        </section>";

        $page->footer();
    }

    public function showCreateForm()
    {
        if (isset($_POST["back"])) {
            header("Location: .");
        }
        if (isset($_POST["create"])) {
            if (empty($_POST["name"]) && empty($_POST["content"])) {
                header("Location: .");
            } else if (empty($_POST["name"])) {
                $this->errors["empty_name"] = 0;
            } else {
                $result = $this->collectFile($_SESSION["username"], $_POST["name"], $_POST["content"]);

                if ($result) {
                    $this->errors["storage"] = 1;
                    header("Location: .");
                } else {
                    $this->errors["storage"] = 0;
                }
            }
        }

        $page = new PageElement();
        $page->head(true);
        $page->header($_SESSION["name"]);

        echo "<section class='file-form'>
            <h2>Új szöveges fájl létrehozása</h2>
            <form method='POST' enctype='multipart/form-data'>";
        $this->showCreateErrors();
        echo "<textarea name='content'></textarea>
                <div>
                    <input class='btn btn-primary' type='submit' name='create' value='Feltöltés'>
                    <input class='btn btn-light' type='submit' name='back' value='Vissza'>
                </div>
            </form>
        </section>";

        $page->footer();
    }

}