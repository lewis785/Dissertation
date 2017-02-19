<?php
include 'connection.php';

if (isset($_SESSION['username'])){
	$redirect = "../../html/pages/home.php";
	header("Location:".$redirect);
}

mysqli_close($link);