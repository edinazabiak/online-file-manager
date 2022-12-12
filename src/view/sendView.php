<?php

class SendView extends SendController {
    public function __construct(
        private array $errors = [
            "storage" => 1,
            "empty_files" => 1,
            "empty_name" => 1, 
            "not_found_user" => 1
        ],
        private array $files = [],
        private int $total_pages = 0,
        private int $active_page = 1, 
        private array $selected = []
        ) {}

    public function search(): void
    {
        if (isset($_POST["search"])) {
            $_SESSION['search'] = $_POST["search_text"];
            $this->total_pages = $this->countOfPages($_SESSION['search']);
            $this->files = $this->listOfFiles(1, $_SESSION['search']);
        }
    }

    public function all() : void
    {
        if (isset($_POST["all"])) {
            $this->files = $this->listOfFiles(1);
        }
    }

    public function send() : void
    {
        if (isset($_POST["send"])) {
            $name = $_POST["name"];
            if (empty($name)) {
                $this->errors["empty_name"] = 0;
            } else if (!$this->existsUser($name)) {
                $this->errors["not_found_user"] = 0;
            }
            if (empty($_POST["selected_files"])) {
                $this->errors["empty_files"] = 0;
            }

            if ($this->errors["not_found_user"] == 1 && $this->errors["empty_files"] == 1) {
                $this->selected = $_POST["selected_files"];
                if ($this->isThereEnoughStorage($name, $this->selected)) {
                    $_SESSION['search'] = "";
                    header("Location: .");
                }
            }

        }
    }

    public function showErrors()
    {
        if ($this->errors["empty_files"] == 0) {
            echo "<div class='warning-info'>Nincs kiválasztva egy fájl sem!</div>";
        }

        if ($this->errors["empty_name"] == 0) {
            echo "<input class='error' type='text' name='name' placeholder='Kötelező megadni a címzett felhasználónevét!'>";
        } else if ($this->errors["not_found_user"] == 0) {
            echo "<input class='error' type='text' name='name' placeholder='Nem létezik ilyen felhasználónév!'>";
        } else {
            echo "<input type='text' name='name' placeholder='Címzett felhasználóneve'>";
        }
    }

    public function showFiles() : void
    {
        $this->send();
        echo "<section class='file-form'>
        <form method='POST'>
            <div>";
        $this->showErrors();
        echo "  <input type='submit' class='btn btn-primary' name='send' value='Küldés'>
                <input class='btn btn-light' type='submit' name='back' value='Vissza'>
            </div>
            <div class='selectable-box'>";
            $num = 1;
            foreach($this->files as $file) {
                echo "<label class='checkbox-container'>
                    " . $file['name'] . "
                    <input type='checkbox' name='selected_files[]' value='" . $file['id'] . "'>
                    <span class='checkmark'></span>
                </label>";
                $num++;
            }
        echo "</div>
            </form>
        </section>";
    }

    public function showFunctions() : void
    {

        echo "<section class='functions-section'>
            <div></div>
            <div class='btn-forms'>";
        $this->all();
        echo "<form class='btn-forms' method='POST'>
            <button type='submit' class='btn btn-primary' name='all'><ion-icon name='apps-outline'></ion-icon> Mind</button>
        </form>";
        
        $this->search();
        echo "<form class='btn-forms' method='POST'>
        <div class='custom-search'>
            <input type='text' name='search_text' id='search_text' placeholder='Keresés'>
            <button type='submit' id='search' name='search'><ion-icon name='search-outline'></ion-icon></button>
        </div>
        </form>
        </div>
        </section>
        ";
    }

    public function showPagination() : void
    {
        echo "<section class='pagination-section'>";
        for($i = 1; $i <= $this->total_pages; $i++) {
            if ($i == $this->active_page) {
                if ($i == 1) {
                    echo "<p class='pagination-num active'>" . $i . "</p>";
                } else {
                    echo "<p class='pagination-num active'>" . $i . "</p>";
                }
            } else {
                if ($i == 1) {
                    echo "<a class='pagination-num' href='./../send-file'>" . $i . "</a>";
                } else if ($this->active_page == 1) {
                    echo "<a class='pagination-num' href='./send-file/" . $i . "'>" . $i . "</a>";
                } else {
                    echo "<a class='pagination-num' href='./../send-file/" . $i . "'>" . $i . "</a>";
                }
            }
        }
        echo "</section>";
    }

    public function showForm(int $page_num) : void
    {
        if (isset($_POST["back"])) {
            $_SESSION['search'] = "";
            if ($page_num == 1) {
                header("Location: .");
            } else {
                header("Location: ../");
            }
        }

        $this->files = $this->listOfFiles($page_num, $_SESSION['search']);
        $this->total_pages = $this->countOfPages($_SESSION['search']);
        $this->active_page = $page_num;
        
        $page = new PageElement();
        if ($this->active_page == 1) {
            $page->head(true);
            $page->header($_SESSION["name"]);
        } else {
            $page->head(true, true);
            $page->header($_SESSION["name"], true);
        }

        $this->showFunctions();
        $this->showFiles();
        $this->showPagination();
        //$this->deleteModal();

        $page->footer();
    }
}