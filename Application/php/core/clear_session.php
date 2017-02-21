<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 21/02/2017
 * Time: 14:54
 */

//Sets the value of a function to blank
function  empty_session($sessionName)
{
    $_SESSION[$sessionName] = "";
}