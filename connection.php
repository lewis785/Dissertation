<?php

    $link = mysqli_connect('mysql-server-1', 'lm357','abclm357354');
    if(!$link) {
        die('Could not connect to MySQL: ' . mysqli_connect_error());
    }


mysqli_select_db($link,"lm357"); //Selects the Database

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
