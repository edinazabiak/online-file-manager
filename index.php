<?php

declare(strict_types = 1);

session_start();

spl_autoload_register(function ($class) {
    if (file_exists(__DIR__ . "/src/$class.php")) {
        require_once __DIR__ . "/src/$class.php";
        return;
    }

    $directories = array("controller", "model", "view");

    foreach($directories as $dir) {
        if (file_exists(__DIR__ . "/src/$dir/$class.php")) {
            require_once __DIR__ . "/src/$dir/$class.php";
            return;
        }
    }
});

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$router = new Routing();
$router->router($parts);