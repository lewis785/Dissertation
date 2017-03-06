<?php

$link = @mysqli_connect('localhost', 'root','kandersteg');
if (!$link) {
    $link = mysqli_connect('localhost', 'root','');
    if(!$link) {
        die('Could not connect to MySQL: ' . mysqli_connect_error());
    }
}

mysqli_select_db($link,"lab-marker"); //Selects the Database

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


