<?php

$verified = false;

include 'connection.php';  //Runs all the code in the connection.php file before carrying on


//Checks if cookie already exists
if (isset($_COOKIE['confirmation'])) {
	$temp = unserialize($_COOKIE['confirmation']); 	//Breaks the cookie down into its parts and stores it in temp
	$verify = mysqli_stmt_init($link);
	mysqli_stmt_prepare($verify, "SELECT count(*) from userlogin where UserName= ? and Password = ?");	//Counts how many users exist with the password and username in the cookie
	mysqli_stmt_bind_param($verify, 'ss', $temp['user'], $temp['pass']);
	mysqli_stmt_execute($verify);

	$result = mysqli_stmt_get_result($verify);
	$count = $result -> fetch_row();

	//If user exists the count will be 1
	if ($count[0] == 1) {
		$verified = true;
	}
}



	//verify is called from form and the user name and pass are set run code
if (isset($_POST['username']) && isset($_POST['password'])) {

	$user = mysqli_real_escape_string($link, $_POST['username']);	//Sanitys the input from the form name username and stores it as var user
	$pass = mysqli_real_escape_string($link, $_POST['password']);	//Sanities the input from the form name password and stores it as var pass

	$encrypt_password = crypt($pass,"Ba24JDAkfjerio892pp309lE"); //Encrypts pass and stores it as another variable 

	$verify = mysqli_stmt_init($link);

	mysqli_stmt_prepare($verify, 'Select count(*) from userlogin where UserName= ? and Password= ?'); //Counts how many users exist with the Username and Password
	mysqli_stmt_bind_param($verify, 'ss', $user, $encrypt_password);   
	mysqli_stmt_execute($verify); 

	$result = mysqli_stmt_get_result($verify);
	$count = $result -> fetch_row();

	//If user exist then count will be 1 
	if ($count[0] == 1) {
		$temp = array("user" => $user, "pass" => $encrypt_password); //creates an array containing the username and password
		setcookie("confirmation", serialize($temp), 0, "/");   //Creates Cookie with name confirmation and the array 
		$verified = true; //Sets verified to true showing user exists
	}

}


//Checks if user if verified if not then if statement runs
if (!$verified) {
	mysqli_close($link);
	$redirect = "../../HTML/login.php";
	header("Location: ".$redirect);
	exit();
}

mysqli_close($link);
?>