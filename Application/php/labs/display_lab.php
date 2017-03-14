<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 19/02/2017
 * Time: 00:33
 */

require_once "classes/LabDisplay.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

$display = new LabDisplay();

if(isset($_POST['lab']) && isset($_POST['course']))
{
    $course = $_POST["course"];
    $lab = $_POST['lab'];
    echo($display->displayLab($course,$lab));
}
else
{
    if(isset($_POST["student"]))
        $_SESSION["MARKING_STUDENT"] = $_POST["student"];

    $course = $_SESSION["MARKING_COURSE"];
    $lab = $_SESSION["MARKING_LAB"];

    echo($display->displayLab($course,$lab));
}

