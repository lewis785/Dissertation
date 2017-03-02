<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 12:36
 */

include(dirname(__FILE__) . "/../labs/get_labs.php");


//Used so that function can be called by Ajax
if(isset($_POST["type"]))
{
    if($_POST["type"] == "labs")
        labs_button_back();
}


function labs_button_back()
{
    session_start();
    marking_labs_buttons($_SESSION["MARKING_COURSE"], "marking");
    $_SESSION["MARKING_LAB"] = "";
}
