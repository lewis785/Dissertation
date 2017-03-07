<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 00:30
 */


require_once "LecturerLab.php";

if(isset($_POST["course"]) && isset($_POST["lab"]) && isset($_POST["username"]) && isset($_POST["visible"])) {
    $student = new LecturerLab();

    echo($student->get_student_result($_POST["course"], $_POST["lab"], $_POST["username"], $_POST["visible"]));
}
