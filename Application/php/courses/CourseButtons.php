<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 14:25
 */

require_once "Courses.php";

class CourseButtons extends Courses
{


    public function courses_dropdown()
    {
        $result = $this->get_courses();
        foreach ($result as $course) {
            echo "<option value='" . $course . "'>" . $course . "</option>";
        }
    }

    public function courses_marking_button()
    {
        $result = $this->get_courses();
        $output = "";
        if(sizeof($result) > 0) {
            foreach ($result as $course) {
                $output .= "<div class='col-md-6 col-md-offset-3'>
                    <button class='btn btn-success' id='btn-marking' onclick='display_labs_for(\"" . $course . "\")'>" . $course . "</button>
                    </div>";
            }
        }
        return json_encode(array("buttons"=>$output));
    }

    function courses_managing_button()
    {
        $result = get_courses();
        $output = "";
        foreach ($result as $course) {
            $output .= "<div class='col-md-6 col-md-offset-3'>
            <button class='btn btn-success' id='btn-marking' onclick='management_options(\"" . $course . "\")'>" . $course . "</button>
            </div>";
        }
        return json_encode(array("buttons"=>$output));
    }


}

//if(isset($_POST["type"])) {
//    $type = $_POST["type"];
//    if( $type === "marking")
//        courses_marking_button();
//    elseif ($type === "manage")
//        courses_managing_button();
//}