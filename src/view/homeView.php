<?php

class HomeView extends HomeController {

    public function __construct(
        private array $files = [],
        private string $sort_id = "1",
        private string $search = "",
        private int $total_pages = 0,
        private int $active_page = 1, 
        private array $selected = []
        ) {}

    public function downloadFile()
    {
        if (isset($_POST["download"])) {
            $id = $_POST["file_id"];
            $file = $this->chooseFileToDownload($id);
            
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
            $this->chooseFileToDelete($id);
            header("Location: .");
        }
    }

    public function sort() : void
    {
        if (isset($_POST["sort"])) {
            $this->sort_id = $_POST["sort_id"];
            $this->files = $this->listOfFiles($this->sort_id, 1);
        }
    }

    public function search(): void
    {
        if (isset($_POST["search"])) {
            $this->search = $_POST["search_text"];
            $this->total_pages = $this->countOfPages($this->sort_id, $this->search);
            $this->files = $this->listOfFiles($this->sort_id, 1, $this->search);
        }
    }

    public function all() : void
    {
        if (isset($_POST["all"])) {
            $this->files = $this->listOfFiles($this->sort_id, 1);
        }
    }

    public function chooseFile()
    {
        if (isset($_POST["send-file"])) {
            echo "Küld";
        }
    }

    public function showFiles() : void
    {
        echo "<section class='list-box'>
        <table>
            <tr>
                <th></th>
                <th>Fájl neve</th>
                <th>Tulajdonos</th>
                <th>Létrehozás dátuma</th>
                <th>Utolsó módosítás</th>
                <th></th>
            </tr>";
            $num = 1;
            foreach($this->files as $file) {
                echo "
                <tr>
                    <td>" . $num . "</td>
                    <td>" . $file['name'] . "</td>
                    <td>";
                if ($file['user_id'] == $file['original_user_id']) {
                    echo $file['user_id'];
                } else {
                    echo $file['user_id'] . "<span class='original_user'>(küldte: " . $file['original_user_id'] . ")</span>";
                }
                echo "</td>
                    <td>" . $file['created_at'] . "</td>
                    <td>" . $file['modify_at'] . "</td>
                    <td>";
                echo "<form method='POST' class='btn-forms'>";
                if ($this->active_page == 1) {
                    echo "<a href='file/" . hash("md2", $file['id']) . "' name='modify' class='btn btn-primary btn-icon'><ion-icon name='create-outline'></ion-icon></a>";
                } else {
                    echo "<a href='../file/" . hash("md2", $file['id']) . "' name='modify' class='btn btn-primary btn-icon'><ion-icon name='create-outline'></ion-icon></a>";
                }
                echo "
                        <button type='submit' name='download' class='btn btn-secondary btn-icon'><ion-icon name='cloud-download-outline'></ion-icon></button>
                        <input type='hidden' name='file_id' value='" . $file['id'] . "'>
                        <button type='submit' name='delete' class='btn btn-delete btn-icon'><ion-icon name='trash-outline'></ion-icon></button>    
                    </form>
                    </td>
                </tr>";
                $num++;
            }
        echo "</table></section>";
    }

    public function showFunctions() : void
    {

        echo "
        <section class='functions-section'>
            <form class='btn-forms' method='POST'>";
        if ($this->active_page == 1) {
            echo "<a href='./upload' class='btn btn-primary' name='new'><ion-icon name='cloud-upload-outline'></ion-icon> Fájl feltöltése</a>
            <a href='./add-new-file' class='btn btn-secondary' name='new'><ion-icon name='add-outline'></ion-icon> Új fájl létrehozása</a>";
            //<a href='.' class='btn btn-light' name='send-file'><ion-icon name='send-outline'></ion-icon> Fájl küldése</a>
        } else {
            echo "<a href='../upload' class='btn btn-primary' name='new'><ion-icon name='cloud-upload-outline'></ion-icon> Fájl feltöltése</a>
            <a href='../add-new-file' class='btn btn-secondary' name='new'><ion-icon name='add-outline'></ion-icon> Új fájl létrehozása</a>";
        }
        echo "
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
                    echo "<a class='pagination-num' href='./../home'>" . $i . "</a>";
                } else {
                    echo "<a class='pagination-num' href='./home/" . $i . "'>" . $i . "</a>";
                }
            }
        }
        echo "</section>";
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

    public function showHome(int $page_num) : void
    {
        $this->files = $this->listOfFiles($this->sort_id, $page_num);
        $this->total_pages = $this->countOfPages($this->sort_id);
        $this->active_page = $page_num;

        $this->deleteFile();
        $this->downloadFile();
        
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