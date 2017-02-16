<?php
include 'connection.php';
session_start();

if (isset($_SESSION['username'])){
	$redirect = "../../html/pages/home.php";
	header("Location:".$redirect);
}