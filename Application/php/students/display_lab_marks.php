<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:54
 */

require_once "get_student_courses.php";
require_once (dirname(__FILE__)."/../labs/get_labs.php");

$courses = get_student_courses();
$resultsTable = "";

foreach($courses as $course)
{
    $labs = get_labs($course);
    $resultsTable.="<div class='col-md-12 results-course-row'><div class='col-md-6 col-md-offset-3'>$course</div></div>";
    foreach($labs as $lab)
    {
        $resultsTable.="<div class='col-md-12 results-lab-row'><div class='col-md-3 col-md-offset-3'>$lab[0]</div></div>";
    }

}
echo $resultsTable;