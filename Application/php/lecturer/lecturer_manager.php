<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 12/03/2017
 * Time: 18:11
 */

require_once "Lecturer.php";
require_once (dirname(__FILE__)."/../admin/AdminForms.php");

if(isset($_POST["action"]) && isset($_POST["course"]) && isset($_POST["lecturer"]))
{
    $lecturer = new Lecturer();
    $action = $_POST["action"];

    if($action === "add")
        echo($lecturer->addLectureToCourse($_POST["course"], $_POST["lecturer"]));
    elseif($action === "remove")
        echo($lecturer->removeLectureFromCourse($_POST["course"], $_POST["lecturer"]));

}
elseif ($_POST["action"] === "filter" && isset($_POST["filter"]))
{
    $admin = new AdminForms();
    echo($admin->filterLecturerTable($_POST["course"], $_POST["filter"]));
}

//$admin = new AdminForms();
//echo($admin->filterLecturerTable("Software Development 1", "s"));