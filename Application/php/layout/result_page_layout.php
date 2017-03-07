<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 05/03/2017
 * Time: 18:18
 */

include (dirname(__FILE__)."/../core/connection.php");
include (dirname(__FILE__)."/../core/check_access_level.php");

if(has_access_level($link,"lecturer"))
{
    echo "<script type='text/javascript' src='../../js/lecturer/lecturer_lab_results.js'></script>";
    include(dirname(__FILE__) . "/../lecturer/lecturer_lab_results.php");
}
else {
    echo "<script type='text/javascript' src='../../js/student/student_lab_results.js'></script>";
    include(dirname(__FILE__) . "/../students/display_lab_marks.php");
}