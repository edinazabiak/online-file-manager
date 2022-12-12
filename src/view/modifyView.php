<?php

class ModifyView extends ModifyController {

    public function __construct(
        private array $errors = [
            "storage" => 1,
            "empty_name" => 1
        ],
        private array $file = [],
        private bool $success = false,
        private string $content = ""
    ) {}

    public function showErrors() : void
    {
        if ($this->errors["empty_name"] == 0) {
            echo "<input class='error' type='text' name='name' placeholder='Kötelező nevet adni a fájlnak!'>";
        } else {
            echo "<input type='text' name='name' placeholder='Fájl neve'>";
        }
    }

    public function validUser() : void
    {
        $hash_id = explode("/", $_SERVER["REQUEST_URI"])[3];
        $valid_user = $this->validUserAndFile($hash_id);

        if ($valid_user == false) {
            header("Location: ./../");
        }
    }

    public function getFile(): void
    {
        $hash_id = explode("/", $_SERVER["REQUEST_URI"])[3];
        $this->file = $this->getDatasOfFile($hash_id);
    }

    public function showModifyForm() : void
    {
        $this->validUser();
        $this->getFile();
        if ($this->file[2] == "txt") {
            $this->content = $this->getContentOfFile($this->file[1] . "." . $this->file[2]);
        }

        if (isset($_POST["back"])) {
            header("Location: .");
        }
        if (isset($_POST["modify"])) {
            if (empty($_POST["name"]) && empty($_POST["content"]) && $this->file[2] == "txt") {
                header("Location: .");
            } else if (empty($_POST["name"]) && $this->file[2] != "txt") {
                header("Location: .");
            } else if (!empty($_POST["name"]) && $this->file[2] != "txt") {
                $this->modifyName($this->file[0], $_POST["name"] . "." . $this->file[2]);
                $this->success = true;
            } else if (!empty($_POST["name"]) && $this->file[2] == "txt"){
                $this->modifyFile($this->file[0], $_POST["name"], $_POST["content"]);
                $this->content = $this->getContentOfFile($this->file[1] . "." . $this->file[2]);
                $this->success = true;
            }
        }

        $page = new PageElement();
        $page->head(true, true);
        $page->header($_SESSION["name"], true);

        echo "<section class='file-form'>
            <h2>Fájl módosítása</h2>";
        if ($this->success == true) {
            echo "<div class='success-info'>Sikeres mentés!</div>";
        }
        echo "
            <form method='POST' enctype='multipart/form-data'>
                <input type='text' name='name' placeholder='Fájl neve' value='" . $this->file[1] . "'>";
        if ($this->file[2] == "txt") {
            echo "<textarea name='content'>" . $this->content . "</textarea>";
        }
        echo "
                <div>
                    <input class='btn btn-primary' type='submit' name='modify' value='Megerősítés'>
                    <input class='btn btn-light' type='submit' name='back' value='Vissza'>
                </div>
            </form>
        </section>";

        $page->footer();
    }
}