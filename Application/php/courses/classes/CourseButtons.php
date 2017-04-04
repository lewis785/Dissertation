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
        $result = $this->getCourses();
        $dropList = "";
        foreach ($result as $course) {
            $dropList .= "<option value='" . $course . "'>" . $course . "</option>";
        }
        return $dropList;
    }

    public function courses_marking_button()
    {
        $result = $this->getCourses();
        $output = "";
        if(sizeof($result) > 0) {
            foreach ($result as $course) {
                $output .= "<div class='col-md-6 col-md-offset-3'>
                    <button class='btn btn-success btn-text-wrap' id='btn-marking' onclick='display_labs_for(\"" . $course . "\")'>" . $course . "</button>
                    </div>";
            }
        }
        return json_encode(array("buttons"=>$output));
    }

    function courses_managing_button()
    {
        $result = $this->getCourses();
        $output = "";
        foreach ($result as $course) {
            $output .= "<div class='col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2 '>
            <button class='btn btn-success btn-text-wrap' id='btn-marking' onclick='management_options(\"" . $course . "\")'>" . $course . "</button>
            </div>";
        }
        return json_encode(array("buttons"=>$output));
    }
}