<?php

class PageElement {

    public function head()
    {
        echo "<!DOCTYPE html>
        <html lang='hu'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Fájlkezelő</title>

                <link rel='stylesheet' href='./assets/main.css'/>
            </head>
            <body>
            <main>";
    }

    public function footer()
    {
        echo "</main>
        <footer>Fájlkezelő &copy; 2022</footer>
        </body></html>";
    }
}