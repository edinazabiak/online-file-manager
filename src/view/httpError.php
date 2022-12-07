<?php

class HttpError {

    public function __construct()
    {}

    public function error()
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
                <div class='http-error'>
                    <h1>404 hibakód</h1>
                    <h2>Oopsz! Hiba történt az oldal betöltésekor!</h2>
                </div>
            </body>
        </html>";
    }
}