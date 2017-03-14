<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 08/03/2017
 * Time: 19:41
 */
require_once "ConnectDB.php";

class LoginManager
{
    private $verified;

    function __construct()
    {
        $this->verified = false;
    }


    public function verify()
    {
        $con = new ConnectDB();

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $this->login($con->link, $_POST["username"], $_POST["password"]);
        }
        elseif (isset($_SESSION['username']) && isset($_SESSION['password'])) {
            $this->validSession($con->link);
        }

        mysqli_close($con->link);
        //Checks if user if verified if not then if statement runs
        if (!$this->verified) {

            $redirect = "../../html/pages/login.php";
            header("Location: ".$redirect);
        }
    }

    public function signOut()
    {
        session_start();
        session_unset();

        header("Location:../../html/pages/login.php"); //Send user to login screen
    }

    public function checkSignedIn()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();

        if (isset($_SESSION['username'])){
            $redirect = "../../html/pages/labresults.php";
            header("Location:".$redirect);
        }
    }


    //Checks if cookie already exists
    private function validSession($link)
    {
        $verify = mysqli_stmt_init($link);
        mysqli_stmt_prepare($verify, "SELECT count(*) FROM user_login WHERE username= ? AND password = ?");
        mysqli_stmt_bind_param($verify, 'ss', $_SESSION['username'], $_SESSION['password']);
        mysqli_stmt_execute($verify);
        $result = mysqli_stmt_get_result($verify);
        $user = $result -> fetch_row();
        $this->verified =  ($user[0] == 1);
    }

    private function login($link, $username, $password)
    {

        $encrypt_password = $password;
        //	$encrypt_password = crypt($pass,"Ba24JDAkfjerio892pp309lE"); //Encrypts pass and stores it as another variable

        $verify = mysqli_stmt_init($link);
        mysqli_stmt_prepare($verify, 'SELECT count(*) FROM user_login WHERE username= ? AND password= ?'); //Counts how many users exist with the Username and Password
        mysqli_stmt_bind_param($verify, 'ss', $username, $encrypt_password);
        mysqli_stmt_execute($verify);
        $result = mysqli_stmt_get_result($verify)-> fetch_row();


        //If user exist then count will be 1
        if ($result[0] == 1) {
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;

            $verify = mysqli_stmt_init($link);
            mysqli_stmt_prepare($verify, 'SELECT ua.access_level FROM user_login as ul JOIN user_access as ua on ul.accessLevel = ua.access_id  WHERE username= ? AND password= ?'); //Counts how many users exist with the Username and Password
            mysqli_stmt_bind_param($verify, 'ss', $username, $encrypt_password);
            mysqli_stmt_execute($verify);
            $level = mysqli_stmt_get_result($verify)-> fetch_row();

            $_SESSION["accesslevel"] = $level[0];
            $this->verified = true; //Sets verified to true showing user exists
        }

    }



//verify is called from form and the user name and pass are set run code


}