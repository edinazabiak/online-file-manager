<?php

class Routing {

    public function router($parts)
    {
        $login = new LoginView();
        $home = new HomeView();
        $upload = new UploadView();
        $profile = new ProfileView();
        $modify = new ModifyView();
        $send = new SendView();

        if (isset($_SESSION["username"]) && $_SESSION["username"] != null) {
            if ($parts[2] == "home") {
                if (empty($parts[3])) {
                    $home->showHome(1); 
                } else {
                    $home->showHome(intval($parts[3])); 
                }
            } else if ($parts[2] == "upload") {
                $upload->showUploadForm();
            } else if ($parts[2] == "profile") {
                $profile->showProfile();
            } else if ($parts[2] == "add-new-file") {
                $upload->showCreateForm();
            } else if ($parts[2] == "file" && $parts[3] != "") {
                $modify->showModifyForm();
            } else if ($parts[2] == "send-file") {
                if (empty($parts[3])) {
                    $send->showForm(1);
                } else {
                    $send->showForm(intval($parts[3]));
                }
            } else if ($parts[2] == "sign-up" || $parts[2] == ""){
                header("Location: home");
            } else {
                http_response_code(404);
                $error = new HttpError();
                $error->error();
            }
        } else {
            if ($parts[2] == "home" || $parts[2] == "upload" || $parts[2] == "profile" || $parts[2] == "add-new-file" || $parts[2] == "file" || $parts[2] == "send-file") {
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