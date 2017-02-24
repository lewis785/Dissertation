<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 01:45
 */

include "get_students.php";
include "get_labs.php";
include(dirname(__FILE__)."/../courses/get_courses.php");


if(isset($_POST["type"]))
{
    if($_POST["type"] == "course")
        courses_button_json();
}
courses_button_json();

function courses_button_json()
{
    $result = get_courses();
    $output = "";
    foreach ($result as $row) {
        foreach ($row as $course) {
            $output .= "<div class='col-md-6 col-md-offset-3'>
                     <button class='btn btn-success' id='btn-course' onclick='display_labs_for(\"".$course."\")'>".$course."</button>
                  </div>";
        }
    }
    echo json_encode(array('buttons'=>$output));
}