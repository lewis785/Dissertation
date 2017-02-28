<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 26/02/2017
 * Time: 22:29
 */

require "lab_total_mark.php";                                                                               //Includes functions to get labs total marks
require "can_mark_lab.php";
require_once (dirname(__FILE__)."/../courses/get_courses.php");                                             //Includes functions to get courses
require_once (dirname(__FILE__)."/../labs/get_labs.php");                                                   //Includes functions to get labs


if(isset($_POST["display_table"]))
{
    if ($_POST["display_table"] === "manage-table")
        display_lab_table();
}
else
{
    echo json_encode(array("success"=>false, "error-message"=>"Failed to post required information"));      //Echos JSON Object with error message
}
//Function returns a JSON object containing the html for the labs table
function display_lab_table()
{
    include (dirname(__FILE__)."/../core/connection.php");                                  //Makes connect to the DB
    require_once (dirname(__FILE__)."/../core/check_access_level.php");                     //Includes functions to check user has required access
    require_once (dirname(__FILE__)."/../labs/get_lab_id.php");                             //Includes function to obtain lab ids;

    $output = "<table class=\"table table-responsive labs-table\"'><tbody>";                //Creates output variable containing start code for table
    if (has_access_level($link, "lecturer")) {                                              //Checks user has access level of lecturer
        $courses = get_courses();                                                           //Gets all the courses the lecturer has access to

        while ($course = $courses->fetch_row()) {                                           //For loop through each course
            $labs = get_labs($course[0]);                                                   //Stores all the labs relating to the course
            $output.= "<tr><td class='btn-info course-row'\" colspan=3>".$course[0]."</td></tr>";   //Insert Row to filling it with the course title
            if(sizeof($labs) > 0) {                                                         //Checks that there is at least one lab
                foreach ($labs as $lab) {                                                   //For loop through each lab the course has
                    $id = get_lab_id($link, $course[0], $lab);                              //Gets the labID for the lab
                    $totalMark = lab_total_mark($link, $course[0],$lab);

                    if (is_lab_markable($link,$course[0],$lab))
                        $buttonChecked = "checked='checked' onclick='lab_markable(".$id.",\"false\")'";
                    else
                        $buttonChecked = "onclick='lab_markable(".$id.",\"true\")'";


                    $output .= "<tr id='lab-".$id."'><td class='lab-row col-md-6'>" . $lab . "</td>";
                    $output .= "<td class='col-md-1'>".$totalMark."</td>";
                    $output .= "<td class='col-md-1'><input id='check-".$id."' type='checkbox'".$buttonChecked." value=''></td>";
                    $output .= "<td class='col-md-2'><button class='btn btn-warning col-md-6 col-md-offset-3'onclick='*'>Edit</button></td>";
                    $output .= "<td class='col-md-2'><button class='btn btn-danger col-md-6 col-md-offset-3' onclick='delete_popup(".$id.")'>Delete</button>";
                    $output .= "</td></tr>";
                }
            }
            else                                                                            //If course has no labs
                $output .= "<tr><td colspan=3><div class='col-md-offset-5 col-md-3'>No Labs Exist</div></td></tr>";     //Adds row stating course has no labs
        }
        $output.="</tbody></table>";                                                        //Adds closing tags for table
        echo json_encode(array("success"=> true, "table"=> $output));                       //Echos JSON Object containing table
    }
    else {                                                                                  //If user does not have lecturer access
        echo json_encode(array("success"=>false, "error-message"=>"You do not have access to run this function"));  //Echos JSON Object stating load failed and an error message
    }

    mysqli_close($link);                                                                    //Closes the DB connection
}