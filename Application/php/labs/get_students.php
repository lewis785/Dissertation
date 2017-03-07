<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 17:32
 */
require_once "LabStudents.php";

if(isset($_POST["type"])) {

    $student = new LabStudents();
    echo($student->get_students_buttons());

}