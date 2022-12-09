<?php

class HomeView extends HomeController {

    public function __construct(
        private array $files = [],
        private string $sort_id = "1",
        private string $search = ""
        ) {}

    public function downloadFile()
    {
        if (isset($_POST["download"])) {
            $id = $_POST["file_id"];
            $file = $this->chooseFileToDownload($id, $_SESSION["username"]);
            
            $filename = "./assets/files/" . $_SESSION["username"] . "/" . basename($file[0]["original_name"]);

            if (file_exists($filename)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file[0]["name"]).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length:' . $file[0]["size"]);
                readfile($filename);
                exit;
            }
        }
    }

    public function deleteFile() : void
    {
        if (isset($_POST["delete"])) {
            $id = $_POST["file_id"];
            $this->chooseFileToDelete($id, $_SESSION["username"]);
            header("Location: .");
        }
    }

    public function sort() : void
    {
        if (isset($_POST["sort"])) {
            $this->sort_id = $_POST["sort_id"];
            $this->files = $this->listOfFiles($_SESSION["username"], $this->sort_id);
        }
    }

    public function search(): void
    {
        if (isset($_POST["search"])) {
            $this->search = $_POST["search_text"];
            $this->files = $this->listOfFiles($_SESSION["username"], $this->sort_id, $this->search);
        }
    }

    public function all() : void
    {
        if (isset($_POST["all"])) {
            $this->files = $this->listOfFiles($_SESSION["username"], $this->sort_id);
        }
    }

    public function showFiles() : void
    {
        echo "<section class='list-box'>
        <table>
            <tr>
                <th>Fájl neve</th>
                <th>Tulajdonos</th>
                <th>Létrehozás dátuma</th>
                <th>Utolsó módosítás</th>
                <th></th>
            </tr>";
            foreach($this->files as $file) {
                echo "
                <tr id='" . $file['id'] . "'>
                    <td>" . $file['name'] . "</td>
                    <td>" . $file['user_id'] . "</td>
                    <td>" . $file['created_at'] . "</td>
                    <td>" . $file['modify_at'] . "</td>
                    <td>";
                echo "<form method='POST' class='btn-forms'>
                        <a href='file/" . hash("md2", $file['id']) . "' name='modify' class='btn btn-primary btn-icon'><ion-icon name='create-outline'></ion-icon></a>
                        <button type='submit' name='download' class='btn btn-secondary btn-icon'><ion-icon name='cloud-download-outline'></ion-icon></button>
                        <input type='hidden' name='file_id' value='" . $file['id'] . "'>
                        <button type='submit' name='delete' class='btn btn-delete btn-icon'><ion-icon name='trash-outline'></ion-icon></button>    
                    </form>
                    </td>
                </tr>";
            }
        echo "</table></section>";
    }

    public function showFunctions() : void
    {

        echo "
        <section class='functions-section'>
            <form class='btn-forms' method='POST'>
                <a href='upload' class='btn btn-primary' name='new'><ion-icon name='cloud-upload-outline'></ion-icon> Fájl feltöltése</a>
                <a href='add-new-file' class='btn btn-secondary' name='new'><ion-icon name='add-outline'></ion-icon> Új fájl létrehozása</a>
                <a href='send-file' class='btn btn-light' name='new'><ion-icon name='send-outline'></ion-icon> Fájl küldése</a>
            </form>
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
        </form>";

        $this->sort();
        echo "
        <form class='btn-forms' method='POST'>
                <div class='custom-select'>
                    <div class='selected-mode'>";
        if ($this->sort_id == "1") {
            echo "<input type='hidden' name='sort_id' id='sort_id' value='1'>
            <p>Fájlok neve szerint</p>";
        } else {
            echo "<input type='hidden' name='sort_id' id='sort_id' value='2'>
            <p>Utolsó módosítás szerint</p>";
        }

        echo "                        
                        <button type='submit' id='sort' name='sort'><ion-icon name='search-outline'></ion-icon></button>
                    </div>
                    <ul class='select-options'>
                        <li class='option' id='1'>Fájlok neve szerint</li>
                        <li class='option' id='2'>Utolsó módosítás szerint</li>
                    </ul>
                </div>
                </form>
            </div>
        </section>
        ";
    }

    // public function deleteModal() : void
    // {
    //     if (isset($_POST["delete"])) {
    //         $id = $_POST["delete_id"];
    //         $this->chooseFileToDelete($id, $_SESSION["username"]);
    //         header("Location: .");
    //     }
    //     echo "<div class='modal'>
    //         <h3>Biztos törölni akarja?</h3>
    //         <form method='POST' class='btn-forms'>
    //             <input type='hidden' name='delete_id' id='delete_id'>
    //             <input type='submit' class='btn btn-primary btn-modal btn-back' value='Mégsem'> 
    //             <input type='submit' class='btn btn-delete btn-modal' id='delete' name='delete' value='Törlés megerősítése'>
    //         </form>
    //     </div>";
    // }

    public function showHome() : void
    {
        $this->files = $this->listOfFiles($_SESSION["username"], $this->sort_id);

        $this->deleteFile();
        $this->downloadFile();
        
        $page = new PageElement();
        $page->head(true);
        $page->header($_SESSION["name"]);

        $this->showFunctions();
        $this->showFiles();
        //$this->deleteModal();

        $page->footer();
    }
}