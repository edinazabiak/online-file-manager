<?php

class PageElement {

    public function head(bool $existsHeader = false, bool $fileHead = false) : void
    {
        echo "<!DOCTYPE html>
        <html lang='hu'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Fájlkezelő</title>";
        if ($fileHead == true) {
            echo "<link rel='stylesheet' href='./../assets/main.css'/>

            <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js'></script>
            <script src='./../assets/main.js'></script>";
        } else {
            echo "<link rel='stylesheet' href='./assets/main.css'/>

            <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js'></script>
            <script src='./assets/main.js'></script>";
        }
        echo "  
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
            <h2>Üdv, $username!</h2>
            <form class='btn-forms' method='POST'>
                <input class='btn btn-primary' type='submit' name='logout' value='Kilépés'>
                <input class='btn btn-light' type='submit' name='profile' value='Profil'>
            </form>
        </header>
        <main>";

        if (isset($_POST["logout"])) {
            // unset($_COOKIE['username']);
            // setcookie("username", null, time()-3600);
            session_unset();
            session_destroy();
            header("Location: .");
        }

        if (isset($_POST["profile"])) {
            header("Location: profile");
        }
    }

    public function footer() : void
    {
        echo "</main>
        <footer>Fájlkezelő &copy; 2022</footer>
        </body></html>";
    }
}