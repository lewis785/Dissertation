<?php

//$link = mysqli_connect('localhost', 'root','');    //Change to your mysql password
$link = mysqli_connect('localhost', 'root','kandersteg');    //Change to your mysql password
if (!$link) { 
	die('Could not connect to MySQL: ' . mysqli_connect_error()); 
} 

mysqli_select_db($link,"lab-marker"); //Selects the Database

