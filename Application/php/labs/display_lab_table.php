<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 26/02/2017
 * Time: 22:29
 */

require "lab_total_mark.php";
require_once (dirname(__FILE__)."/../courses/get_courses.php");
require_once (dirname(__FILE__)."/../labs/get_labs.php");

display_lab_table();

function display_lab_table()
{
    include (dirname(__FILE__)."/../core/connection.php");
    require_once (dirname(__FILE__)."/../core/check_access_level.php");
    require_once (dirname(__FILE__)."/../labs/get_lab_id.php");

    $output = "<table class=\"table table-responsive labs-table\"'><tbody>";
    if (has_access_level($link, "lecturer")) {
        $courses = get_courses();

        while ($course = $courses->fetch_row()) {
            $labs = get_labs($course[0]);
            $output.= "<tr><td class='btn-info course-row'\" colspan=3>".$course[0]."</td></tr>";
            if(sizeof($labs) > 0) {
                foreach ($labs as $lab) {
                    $id = get_lab_id($link, $course[0], $lab);
                    $output .= "<tr id='lab-".$id."'><td class='lab-row col-md-8'>" . $lab . "</td>
                              <td class='col-md-2'><button class='btn btn-warning col-md-6 col-md-offset-3'onclick='*'>Edit</button></td>
                              <td class='col-md-2'><button class='btn btn-danger col-md-6 col-md-offset-3' onclick='delete_popup(".$id.")'>Delete</button></td></tr>";
                }
            }
            else
                $output .= "<tr><td colspan=3><div class='col-md-offset-5 col-md-3'>No Labs Exist</div></td></tr>";
        }
        $output.="</body></table>";
        echo $output;

    } else {
        echo "You do not have access to run this function";
    }


    mysqli_close($link);
}