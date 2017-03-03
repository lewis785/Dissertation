<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 16:02
 */


if(isset($_POST["course"]) && isset($_POST["type"])) {
    if($_POST["type"] === "marking")
        marking_labs_buttons($_POST["course"]);
}

function get_labs($course)
{
    include(dirname(__FILE__)."/../core/connection.php");
    require_once(dirname(__FILE__)."/../courses/course_checks.php");
    require_once(dirname(__FILE__)."/../core/check_access_level.php");
    $labsArray = [];

    $get_labs = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_labs, "SELECT l.labName, l.canMark FROM labs AS l 
                                        JOIN courses AS c ON l.courseRef = c.courseID 
                                        WHERE c.courseName = ? ORDER BY l.labID");
    mysqli_stmt_bind_param($get_labs, 's', $course);
    mysqli_stmt_execute($get_labs);
    $result = mysqli_stmt_get_result($get_labs);

    while($lab = $result->fetch_row()) {
        array_push($labsArray, $lab);
    }

    mysqli_close($link);
    return $labsArray;
}


function get_markable_labs($course)
{
    include(dirname(__FILE__)."/../core/connection.php");
    require_once(dirname(__FILE__)."/../courses/course_checks.php");

    $labs = [];
    if (can_mark_course($link,$course)) {
       $labs = get_labs($course);
    }
    mysqli_close($link);
    return $labs;
}





function marking_labs_buttons($course)
{

    include(dirname(__FILE__)."/../core/connection.php");
    $_SESSION["MARKING_COURSE"] = $course;
    $result = get_labs($course);
    $isLecturer = has_access_level($link,"lecturer");

    $buttons = "";

    if(sizeof($result)>0) {
        foreach ($result as $lab) {
            if ($isLecturer || $lab[1] === "true")
                $buttons .= "<div class='col-md-6 col-md-offset-3'>
                          <button class='btn btn-success' id='btn-marking' onclick='display_students_for(\"" . $lab[0] . "\")'>" . $lab[0] . "</button>
                         </div>";
        }
    }
    else
        $buttons = "<div class='col-md-6 col-md-offset-3'>
                        No Labs Currently exist to be marked
                    </div>";

    echo json_encode(array('successful'=>true, 'buttons'=>$buttons));

    mysqli_close($link);
}