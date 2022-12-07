<?php

class HomeView extends HomeController {

    public function __construct() {}

    public function showHome()
    {
        $page = new PageElement();
        $page->head(true);
        $page->header($_SESSION["username"]);

        $this->showFunctions();
        $this->showFiles();

        $page->footer();
    }

    public function showFiles()
    {
        $files = $this->listOfFiles($_SESSION["username"]);
        echo "<section class='list-box'>
        <table>
            <tr>
                <th>Fájl neve</th>
                <th>Tulajdonos</th>
                <th>Létrehozás dátuma</th>
                <th>Utolsó módosítás</th>
                <th></th>
            </tr>";
            foreach($files as $file) {
                echo "
                <tr>
                    <td>" . $file['name'] . "</td>
                    <td>" . $file['user_id'] . "</td>
                    <td>" . $file['created_at'] . "</td>
                    <td>" . $file['modify_at'] . "</td>
                    <td class='modify-icons'>";
                    if ($file["type"] == "text") {
                        echo "<ion-icon name='create-outline'></ion-icon>";
                    }
                echo "<span class='btn btn-primary'><ion-icon name='cloud-download-outline'></ion-icon></span> 
                <span class='btn btn-delete'><ion-icon name='trash-outline'></ion-icon></span>
                    </td>
                </tr>";
            }
        echo "</table></section>";
    }

    public function showFunctions()
    {
        echo "
        <section>
            <form class='btn-forms' method='POST'>
                <a href='upload' class='btn btn-primary' name='new'><ion-icon name='cloud-upload-outline'></ion-icon> Fájl feltöltése</a>
                <a href='add-new-file' class='btn btn-secondary' name='new'><ion-icon name='add-outline'></ion-icon> Új fájl létrehozása</a>
                <a href='send-file' class='btn btn-light' name='new'><ion-icon name='send-outline'></ion-icon> Fájl küldése</a>
            </form>
        </section>
        ";
    }
}