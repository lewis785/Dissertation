<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 12:36
 */

include(dirname(__FILE__) . "/../labs/get_students.php");


if(isset($_POST["type"]))
{
    if($_POST["type"] == "students")
        students_button_back();
}


function students_button_back()
{
    session_start();
    get_students_buttons($_SESSION["MARKING_LAB"]);
}