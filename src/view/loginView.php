<?php

class LoginView extends LoginController
{

    public function __construct(
        private array $loginErrors = [
            "username" => 1, 
            "password" => 1, 
            "match" => 1],
        private array $signUpErrors = [
            "name" => 1, 
            "username" => 1, 
            "email" => 1, 
            "password" => 1, 
            "repassword" => 1, 
            "match" => 1, 
            "validEmail" => 1, 
            "reservedUsername" => 1, 
            "reservedEmail" => 1],
        private string $name = "",
        private string $username = "", 
        private string $email = "", 
        private string $password = "",
        private string $repassword = "",
        ) {}

    public function manageLogin() : void
    {
        $this->loginErrors["username"] = empty($this->username) ? 0 : 1;
        $this->loginErrors["password"] = empty($this->password) ? 0 : 1;
        if ($this->loginErrors["username"] == 1 && $this->loginErrors["password"] == 1) {
            $this->loginErrors["match"] = $this->isExistsUsername($this->username, $this->password);

            if ($this->loginErrors["match"] == 1) {
                //setcookie("username", $_POST["username"], time()+60);
                $_SESSION["username"] = $this->username;
                header("Location: home");
            }
        }
    }

    public function showLoginError() : void
    {
        if ($this->loginErrors["match"] != 1) {
            echo "<p class='error-text'>Helytelen felhasználónév vagy jelszó!</p>
            <input class='error' type='text' id='username' name='username' placeholder='Felhasználónév' value='$this->username'>
            <input class='error' type='password' id='password' name='password' placeholder='Jelszó' value='$this->password'>";
        } else {
            if ($this->loginErrors["username"] == 1) {
                echo "<input type='text' id='username' name='username' placeholder='Felhasználónév' value='$this->username'>";
            }
            else {
                echo "<input class='error' type='text' id='username' name='username' placeholder='Kötelező kitölteni a felhasználónév mezőt!'>";
            }
            if ($this->loginErrors["password"] == 1) {
                echo "<input type='password' id='password' name='password' placeholder='Jelszó' value='$this->password'>";
            }
            else {
                echo "<input class='error' type='password' id='password' name='password' placeholder='Kötelező kitölteni a jelszó mezőt!'>";
            }
        }  
    }

    public function showLogin() : void
    {
        if (isset($_POST['login'])) {
            $this->username = $_POST["username"];
            $this->password = $_POST["password"];
            $this->manageLogin();
        } else if (isset($_POST['signup'])) {
            header("Location: sign-up");
        }
        
        $page = new PageElement();
        $page->head();
        echo "
            <div class='login_system_box'>
                <h1>Bejelentkezés</h1>
                <form method='POST'>";
                $this->showLoginError();
                echo "
                    <div>
                        <input type='submit' class='btn btn-primary' name='login' value='Bejelentkezés'>
                        <input type='submit' class='btn btn-secondary' name='signup' value='Regisztráció'>
                    </div>
                    <a href='forgot-password'>Elfelejtett jelszó</a>
                </form>
            </div>";
        $page->footer();
    }

    public function manageSignUp() : void
    {
        $this->signUpErrors["name"] = empty($this->name) ? 0 : 1;
        $this->signUpErrors["username"] = empty($this->username) ? 0 : 1;
        if ($this->signUpErrors["username"] == 1) {
            $this->signUpErrors["reservedUsername"] = $this->isReservedUsername($this->username);
        } else {
            $this->signUpErrors["reservedUsername"] = 0;
        }

        $this->signUpErrors["email"] = empty($this->email) ? 0 : 1;
        if ($this->signUpErrors["email"] == 1) {
            $this->signUpErrors["validEmail"] = filter_var($this->email, FILTER_VALIDATE_EMAIL) ? 1 : 0;
            if ($this->signUpErrors["validEmail"] == 1) {
                $this->signUpErrors["reservedEmail"] = $this->isReservedEmail($this->email);
            } else {
                $this->signUpErrors["reservedEmail"] = 0;
            }
        }

        $this->signUpErrors["password"] = empty($this->password) ? 0 : 1;
        $this->signUpErrors["repassword"] = empty($this->repassword) ? 0 : 1;
        if ($this->signUpErrors["password"] == 1 && $this->signUpErrors["repassword"] == 1) {
            $this->signUpErrors["match"] = hash_equals($this->password, $this->repassword);
        } else {
            $this->signUpErrors["match"] = 0;
        }

        if ($this->signUpErrors["reservedUsername"] == 1 && $this->signUpErrors["reservedEmail"] == 1 && $this->signUpErrors["match"] == 1) {
            $this->collectDatas($this->name, $this->username, $this->email, $this->password);
            header("Location: .");
        }
    }

    public function showSignUpError() : void
    {
        if ($this->signUpErrors["name"] == 1) {
            echo "<input type='text' id='name' name='name' placeholder='Név' value='$this->name'>";
        } else {
            echo "<input class='error' type='text' id='name' name='name' placeholder='Kötelező kitölteni a név mezőt!'>";
        }
        if ($this->signUpErrors["username"] == 1) {
            if ($this->signUpErrors["reservedUsername"] == 1) {
                echo "<input type='text' id='username' name='username' placeholder='Felhasználónév' value='$this->username'>";
            } else {
                echo "<input class='error' type='text' id='username' name='username' placeholder='Már létezik ilyen felhasználónév!'>";
            }
        } else {
            echo "<input class='error' type='text' id='username' name='username' placeholder='Kötelező kitölteni a felhasználónév mezőt!'>";
        }
        if ($this->signUpErrors["email"] == 1) {
            if ($this->signUpErrors["validEmail"] == 1) {
                if ($this->signUpErrors["reservedEmail"] == 1) {
                    echo "<input type='text' id='email' name='email' placeholder='E-mail cím' value='$this->email'>";
                } else {
                    echo "<input class='error' type='text' id='email' name='email' placeholder='Már létezik ilyen e-mail cím az adatbázisban!'>";
                }
            } else {
                echo "<input class='error' type='text' id='email' name='email' placeholder='Helytelen e-mail cím!'>";
            }
        } else {
            echo "<input class='error' type='text' id='email' name='email' placeholder='Kötelező kitölteni az e-mail cím mezőt!'>";
        }
        if ($this->signUpErrors["password"] == 1 && $this->signUpErrors["repassword"] == 1) {
            if ($this->signUpErrors["match"] == 1) {
                echo "<input type='password' id='password' name='password' placeholder='Jelszó'>
                <input type='password' id='repassword' name='repassword' placeholder='Jelszó megerősítése'>";
            } else {
                echo "<input class='error' type='password' id='password' name='password' placeholder='Nem egyezik a két jelszó!'>
                <input class='error' type='password' id='repassword' name='repassword' placeholder='Nem egyezik a két jelszó!'>";
            }
        } else if ($this->signUpErrors["password"] == 1) {
            echo "<input type='password' id='password' name='password' placeholder='Jelszó'>
            <input class='error' type='password' id='repassword' name='repassword' placeholder='Kötelező megadni a jelszó megerősítését!'>";
        } else if ($this->signUpErrors["repassword"] == 1) {
            echo "<input class='error' type='password' id='password' name='password' placeholder='Kötelező megadni a jelszó mezőt!'>
            <input type='password' id='repassword' name='repassword' placeholder='Jelszó megerősítése'>";
        } else {
            echo "<input class='error' type='password' id='password' name='password' placeholder='Kötelező megadni a jelszó mezőt!'>
            <input class='error' type='password' id='repassword' name='repassword' placeholder='Kötelező megadni a jelszó megerősítését!'>";
        }
    }

    public function showSignUp() : void
    {
        if (isset($_POST["signup"])) {
            $this->name = $_POST["name"];
            $this->username = $_POST["username"];
            $this->email = $_POST["email"];
            $this->password = empty($_POST["password"]) ? "" : hash("sha256", $_POST["password"]);
            $this->repassword = empty($_POST["repassword"]) ? "" : hash("sha256", $_POST["repassword"]);
            $this->manageSignUp();
        } else if (isset($_POST["back"])) {
            header("Location: .");
        }

        $page = new PageElement();
        $page->head();
        echo "
            <div class='login_system_box'>
                <h1>Regisztráció</h1>
                <form method='POST'>";
                $this->showSignUpError();
                echo "
                    <div>
                        <input type='submit' class='btn btn-primary' name='signup' value='Megerősítés'>
                        <input type='submit' class='btn btn-secondary' name='back' value='Vissza'>
                    </div>
                </form>
            </div>";
        $page->footer();
    }
}
