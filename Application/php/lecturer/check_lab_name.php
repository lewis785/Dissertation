<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 04/03/2017
 * Time: 23:11
 */

require_once "LecturerLab.php";

if(isset($_POST["course"]) && isset($_POST["lab"])) {

    $lab = new LecturerLab();
    echo($lab->checkLabName($_POST["course"], $_POST["lab"]));

}


//    include dirname(__FILE__) . "/../../core/connection.php";
//
//    $course = $_POST["course"];
//    $lab = $_POST["lab"];
//
//
//    $checkIfNameExists = mysqli_stmt_init($link);
//    mysqli_stmt_prepare($checkIfNameExists, "SELECT count(*) FROM labs AS l
//                                          JOIN courses AS c ON l.courseRef = c.courseID
//                                           WHERE c.courseName = ? AND l.labName = ?");
//    mysqli_stmt_bind_param($checkIfNameExists, "ss", $course, $lab);
//    mysqli_stmt_execute($checkIfNameExists);
//
//    $result = mysqli_stmt_get_result($checkIfNameExists)->fetch_row();
//
//    mysqli_close($link);
//    echo json_encode(array("exists" => ($result[0] === 1)));
//}