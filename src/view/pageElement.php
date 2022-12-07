<?php

class PageElement {

    public function head(bool $existsHeader = false) : void
    {
        echo "<!DOCTYPE html>
        <html lang='hu'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Fájlkezelő</title>

                <link rel='stylesheet' href='./assets/main.css'/>

                <script type='module' src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js'></script>
                <script nomodule src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>
            </head>
            <body>";
        if (!$existsHeader) {
            echo "<main>";
        }
    }

    public function header(string $username) : void
    {
        echo "<header>
            <h2>$username</h2>
            <form class='btn-forms' method='POST'>
                <input class='btn btn-primary' type='submit' name='logout' value='Kilépés'>
                <input class='btn btn-light' type='submit' name='setting' value='Beállítások'>
            </form>
        </header>
        <main>";

        if (isset($_POST['logout'])) {
            // unset($_COOKIE['username']);
            // setcookie("username", null, time()-3600);
            session_unset();
            session_destroy();
            header("Location: .");
        }
    }

    public function footer() : void
    {
        echo "</main>
        <footer>Fájlkezelő &copy; 2022</footer>
        </body></html>";
    }
}