<?php

declare(strict_types = 1);


spl_autoload_register(function ($class) {
    $directories = array("model", "view", "controller");

    foreach($directories as $dir) {
        if (file_exists(__DIR__ . "/src/$dir/$class.php")) {
            require __DIR__ . "/src/$dir/$class.php";
            return;
        }
    }
});