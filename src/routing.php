<?php

class Routing {

    public function router($parts)
    {
        $login = new LoginView();
        $home = new HomeView();
        $upload = new UploadView();

        if (isset($_SESSION["username"]) && $_SESSION["username"] != null) {
            if ($parts[2] == "home") {
                $home->showHome(); 
            } else if ($parts[2] == "upload") {
                $upload->showForm();
            } else if ($parts[2] == "sign-up") {
                header("Location: .");
            } else {
                header("Location: home");
            }
        } else {
            if ($parts[2] == "home" || $parts[2] == "upload") {
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