<?php

class HomeView extends HomeController {

    public function __construct() {}

    public function show($username)
    {
        echo $username;
        echo "<form method='POST'>
            <input type='submit' name='logout' value='Kilépés'>
        </form>";

        if (isset($_POST['logout'])) {
            // unset($_COOKIE['username']);
            // setcookie("username", null, time()-3600);
            session_unset();
            session_destroy();
            header("Location: .");
        }
    }
}