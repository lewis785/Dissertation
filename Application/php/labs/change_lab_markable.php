<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 28/02/2017
 * Time: 18:23
 */

require_once "LabManager.php";

if(isset($_POST["labID"]) && isset($_POST["newState"]))
{
    $manager = new LabManager();
    echo($manager->changeMarkable());
}


//    require (dirname(__FILE__)."/../core/connection.php");
//    require (dirname(__FILE__)."/../courses/course_checks.php");
//    require (dirname(__FILE__)."/../courses/course_from_lab_id.php");
//
//    $labID = $_POST["labID"];
//    $state = $_POST["newState"];
//    $course = course_from_lab_id($link,$labID);
//
//    if(is_lecturer_of_course($link,$course))
//    {
//        $changeMarkState = mysqli_stmt_init($link);
//        mysqli_stmt_prepare($changeMarkState, "UPDATE labs SET canMark = ? WHERE labID = ?");
//        mysqli_stmt_bind_param($changeMarkState, "si", $state, $labID);
//        $successful = mysqli_stmt_execute($changeMarkState);
//        echo json_encode(array("success"=>$successful));
//    }
//    else
//        echo json_encode(array("success"=>false));
//
//
//
//
//
//    mysqli_close($link);
//}