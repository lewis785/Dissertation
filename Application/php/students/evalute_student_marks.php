<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 22:28
 */

require_once "student_lab_mark.php";
require_once (dirname(__FILE__)."/../labs/lab_total_mark.php");
require_once (dirname(__FILE__)."/../labs/lab_total_mark.php");


function has_full_marks($student,$courseName, $labName)
{
    include (dirname(__FILE__)."/../core/connection.php");
    $maxMark = lab_total_mark($link,$courseName,$labName);
    $studentMark = lab_mark_for_student($link, $student, $courseName, $labName);
    mysqli_close($link);
    return $studentMark == $maxMark;
}

function has_no_marks($student,$courseName, $labName)
{
    include (dirname(__FILE__)."/../core/connection.php");
    $studentMark = lab_mark_for_student($link, $student, $courseName, $labName);
    mysqli_close($link);
    return $studentMark == 0;
}