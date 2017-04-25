<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 13:35
 */
class ConnectDB
{
    public $link;

    function __construct()
    {
        $this->link = mysqli_connect('localhost', 'root','');
        if(!$this->link) {
            die('Could not connect to MySQL: ' . mysqli_connect_error());
        }
        
        mysqli_select_db($this->link,"lab-marker"); //Selects the Database

        if (session_status() == PHP_SESSION_NONE)
            session_start();

    }
}