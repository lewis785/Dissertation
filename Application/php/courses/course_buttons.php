<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 01:32
 */

require_once "get_courses.php";

if(isset($_POST["type"])) {
    $type = $_POST["type"];
    if( $type === "marking")
        courses_marking_button();
    elseif ($type === "manage")
        courses_managing_button();
}



function courses_dropdown()
{
    $result = get_courses();
    foreach ($result as $row) {
        foreach ($row as $course) {
            echo "<option value='" . $course . "'>" . $course . "</option>";
        }
    }
}

function courses_marking_button()
{
    $result = get_courses();
    $output = "";
    if(sizeof($result) > 0) {
        foreach ($result as $courses) {
            foreach ($courses as $course) {
                $output .= "<div class='col-md-6 col-md-offset-3'>
                    <button class='btn btn-success' id='btn-marking' onclick='display_labs_for(\"" . $course . "\")'>" . $course . "</button>
                    </div>";
            }
        }
    }
    echo json_encode(array("buttons"=>$output));
}

function courses_managing_button()
{
    $result = get_courses();
    $output = "";
    foreach ($result as $courses) {
        foreach ($courses as $course) {
            $output .= "<div class='col-md-6 col-md-offset-3'>
            <button class='btn btn-success' id='btn-marking' onclick='management_options(\"" . $course . "\")'>" . $course . "</button>
            </div>";
        }
    }
    echo json_encode(array("buttons"=>$output));
}