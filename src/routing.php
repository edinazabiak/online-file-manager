<?php

class Routing {

    public function router($parts)
    {
        $login = new LoginView();
        $home = new HomeView();

        if (isset($_SESSION["username"]) && $_SESSION["username"] != null) {
            if ($parts[2] == "home") {
                $home->show($_SESSION["username"]); 
            } else if ($parts[2] == "sign-up") {
                header("Location: .");
            } else {
                header("Location: home");
            }
        } else {
            if ($parts[2] == "home") {
                header("Location: .");
            } else if ($parts[2] == "sign-up") {
                $login->showSignUp();
            } else if ($parts[2] != "") {
                http_response_code(404);
                $error = new HttpError();
                $error->error();
            } else {
                $login->showLogin();
            }
        }
    }
}