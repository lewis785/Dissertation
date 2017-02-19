<?php

session_start();
session_unset();

header("Location:../../html/pages/login.php"); //Send user to login screen
