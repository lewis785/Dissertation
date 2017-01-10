<?php

//Checks if cookie confirmation exists
if (isset($_COOKIE['confirmation'])) {
	unset($_COOKIE['confirmation']);//Gets rid of confirmation cookie
    setcookie('confirmation', '', time() - 3600, '/'); // Sets confirmation cookie with a negitave time to make sure it removed
}

header("Location:http://badapple/HTML/login.php"); //Send user to login screen

?>