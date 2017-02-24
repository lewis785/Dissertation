<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 12:36
 */

include(dirname(__FILE__) . "/../labs/get_labs.php");

if(isset($_POST["type"]))
{
    if($_POST["type"] == "labs")
        labs_button_back();
}


function labs_button_back()
{
    session_start();
    get_labs_buttons($_SESSION["MARKING_COURSE"]);
    $_SESSION["MARKING_LAB"] = "";
}
