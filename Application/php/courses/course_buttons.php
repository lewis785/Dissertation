<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 01:32
 */

require_once "classes/CourseButtons.php";

if(isset($_POST["type"])) {
    $courses = new CourseButtons();

    $type = $_POST["type"];
    if( $type === "marking")
        echo($courses->courses_marking_button());
    elseif ($type === "manage")
        echo($courses->courses_managing_button());
}