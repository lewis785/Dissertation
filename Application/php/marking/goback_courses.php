<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 01:45
 */

include(dirname(__FILE__) . "/../courses/get_courses.php");


if(isset($_POST["type"]))
{
    if($_POST["type"] == "course")
        session_start();
        $_SESSION["MARKING_COURSE"] = "";
        courses_button_back();
}

function courses_button_back()
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