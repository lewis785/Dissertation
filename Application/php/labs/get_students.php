<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 17:32
 */
require_once "classes/LabStudents.php";

if(isset($_POST["type"])) {

    $student = new LabStudents();
    if(isset($_POST["filter"]))
        echo($student->studentButtonsFilter($_POST["filter"]));
    else
        echo($student->get_students_buttons());

}