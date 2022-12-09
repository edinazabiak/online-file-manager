<?php

class Routing {

    public function router($parts)
    {
        $login = new LoginView();
        $home = new HomeView();
        $upload = new UploadView();
        $profile = new ProfileView();
        $modify = new ModifyView();

        if (isset($_SESSION["username"]) && $_SESSION["username"] != null) {
            if ($parts[2] == "home") {
                $home->showHome(); 
            } else if ($parts[2] == "upload") {
                $upload->showUploadForm();
            } else if ($parts[2] == "profile") {
                $profile->showProfile();
            } else if ($parts[2] == "add-new-file") {
                $upload->showCreateForm();
            } else if ($parts[2] == "send-file") {
                $profile->showProfile();
            } else if ($parts[2] == "file") {
                $modify->showModifyForm();
            } else {
                header("Location: home");
            }
        } else {
            if ($parts[2] == "home" || $parts[2] == "upload" || $parts[2] == "profile" || $parts[2] == "add-new-file" || $parts[2] == "send-file" || $parts[2] == "file") {
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