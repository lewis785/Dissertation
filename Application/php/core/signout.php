<?php

session_start();
session_unset();

echo S_SESSION["username"];

header("Location:../../html/pages/login.php"); //Send user to login screen
