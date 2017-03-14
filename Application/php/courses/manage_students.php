<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 10/03/2017
 * Time: 23:55
 */

require_once "classes/CourseManager.php";

if(isset($_POST["type"]) && isset($_POST["course"]) && isset($_POST["student"]))
{
    $type = $_POST["type"];
    $course = new CourseManager();

    if($type == "insert")
    {
       echo($course->addStudentToCourse($_POST["course"], $_POST["student"]));
    }
    elseif ($type == "remove")
    {
        echo($course->removeStudentFromCourse($_POST["course"], $_POST["student"]));
    }

}