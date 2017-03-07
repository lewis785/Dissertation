<?php

$verified = false;
include 'connection.php';  //Runs all the code in the connection.php file before carrying on

//Checks if cookie already exists
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {

    $verify = mysqli_stmt_init($link);
    mysqli_stmt_prepare($verify, "SELECT count(*) FROM user_login WHERE username= ? AND password = ?");
    mysqli_stmt_bind_param($verify, 'ss', $_SESSION['username'], $_SESSION['password']);
    mysqli_stmt_execute($verify);
    $result = mysqli_stmt_get_result($verify);
    $user = $result -> fetch_row();
    $verified =  ($user[0] == 1);
}

//verify is called from form and the user name and pass are set run code
if (isset($_POST['username']) && isset($_POST['password'])) {
	$user = mysqli_real_escape_string($link, $_POST['username']);	//Sanitys the input from the form name username and stores it as var user
	$pass = mysqli_real_escape_string($link, $_POST['password']);	//Sanities the input from the form name password and stores it as var pass

    $encrypt_password = $pass;
    echo $user . $encrypt_password;
//	$encrypt_password = crypt($pass,"Ba24JDAkfjerio892pp309lE"); //Encrypts pass and stores it as another variable

	$verify = mysqli_stmt_init($link);
	mysqli_stmt_prepare($verify, 'SELECT count(*) FROM user_login WHERE username= ? AND password= ?'); //Counts how many users exist with the Username and Password
	mysqli_stmt_bind_param($verify, 'ss', $user, $encrypt_password);
	mysqli_stmt_execute($verify);
	$result = mysqli_stmt_get_result($verify)-> fetch_row();

    echo $result[0];
	//If user exist then count will be 1 
	if ($result[0] == 1) {
	    $_SESSION["username"] = $user;
	    $_SESSION["password"] = $pass;

        $verify = mysqli_stmt_init($link);
        mysqli_stmt_prepare($verify, 'SELECT ua.access_level FROM user_login as ul JOIN user_access as ua on ul.accessLevel = ua.access_id  WHERE username= ? AND password= ?'); //Counts how many users exist with the Username and Password
        mysqli_stmt_bind_param($verify, 'ss', $user, $encrypt_password);
        mysqli_stmt_execute($verify);
        $level = mysqli_stmt_get_result($verify)-> fetch_row();

	    $_SESSION["accesslevel"] = $level[0];
		$verified = true; //Sets verified to true showing user exists
	}
}

//Checks if user if verified if not then if statement runs
if (!$verified) {
	mysqli_close($link);
	$redirect = "../../html/pages/login.php";
	header("Location: ".$redirect);
}

mysqli_close($link);
