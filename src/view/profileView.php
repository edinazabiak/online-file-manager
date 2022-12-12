<?php

class ProfileView extends ProfileController {

    public function __construct(
        private array $errors = [
            "password" => 1, 
            "repassword" => 1,
            "match" => 1
        ]
    ) {}

    public function showErrors()
    {
        if ($this->errors["password"] == 0) {
            echo "<input class='error' type='password' name='password' id='password' value='' placeholder='Jelszó'>";
        } else if ($this->errors["match"] == 0) {
            echo "<input class='error' type='password' name='password' id='password' value='' placeholder='Jelszó'>";
        } else {
            echo "<input type='password' name='password' id='password' value='' placeholder='Jelszó'>";
        }
        if ($this->errors["repassword"] == 0) {
            echo "<input class='error' type='password' name='repassword' id='repassword' value='' placeholder='Jelszó megerősítése'>";
        } else if ($this->errors["match"] == 0) {
            echo "<input class='error' type='password' name='repassword' id='repassword' value='' placeholder='Jelszó megerősítése'>";
        } else {
            echo "<input type='password' name='repassword' id='repassword' value='' placeholder='Jelszó megerősítése'>";
        }
    }

    public function showForm() : void
    {
        $user = $this->getDatasAboutUser();

        echo "<section>
            <h2>Adatok megváltoztatása</h2>
            <form method='POST'>
                <input type='text' name='name' id='name' value='" . $user["Name"] . "' placeholder='Név'>
                <div>
                    <input class='btn btn-primary' type='submit' name='modify_name' value='Módosítás'>
                </div>";

        $this->showErrors();

        echo "
                <div>
                    <input class='btn btn-primary' type='submit' name='modify_password' value='Módosítás'>
                    <input class='btn btn-light' type='submit' name='back' value='Vissza'>
                </div>
            </form>
        </section>";
    }

    public function showStatistics() : void
    {
        $result = $this->getNumbersAboutUser();

        echo "<section>
            <h2>Statisztika</h2>
            <p>Tárhely: 15 / " . round(floatval($result["storage"]), 2) . " GB szabad</p>
            <p>Feltöltött fájlok száma: " . $result["number"] . " db</p>
        </section>";
    }

    public function showProfile() : void
    {
        if (isset($_POST["back"])) {
            header("Location: .");
        }
        if (isset($_POST["modify_name"])) {
            if (!empty($_POST["name"])) {
                $_SESSION["name"] = $_POST["name"];
            }
            $this->modifyName($_SESSION["name"]);
                header("Location: .");
        }

        if (isset($_POST["modify_password"])) {
            if (empty($_POST["password"])) {
                $this->errors["password"] = 0;
            }
            if (empty($_POST["repassword"])) {
                $this->errors["repassword"] = 0;
            }
            if ($_POST["password"] != $_POST["repassword"]) {
                $this->errors["match"] = 0;
            }

            if ($this->errors["password"] == 1 && $this->errors["repassword"] == 1 && $this->errors["match"] == 1) {
                $this->modifyPassword(hash("sha256", $_POST["password"]));
                header("Location: .");
            }

        }

        $page = new PageElement();
        $page->head(true);
        $page->header($_SESSION["name"]);

        $this->showStatistics();
        $this->showForm();

        $page->footer();
    }

}