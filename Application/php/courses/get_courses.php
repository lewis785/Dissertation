<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 17/02/2017
 * Time: 17:45
 */

include(dirname(__FILE__) . "/../core/check_access_level.php");


function courses_dropdown(){
    $result = get_courses();
    foreach ($result as $row) {
        foreach ($row as $course) {
            echo "<option value='" . $course . "'>" . $course . "</option>";
        }
    }
}

function courses_button()
{
    $result = get_courses();
    foreach ($result as $row) {
        foreach ($row as $course) {
            echo "<div class='col-md-6 col-md-offset-3'>
                     <button class='btn btn-success' id='btn-course' onclick='display_labs_for(\"".$course."\")'>".$course."</button>
                  </div>";
        }
    }
}

function get_courses()
{
    include(dirname(__FILE__) . "/../core/connection.php");

    if (has_access_level($link, "lecturer")) {
        $get_courses = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_courses, "SELECT c.courseName FROM user_login as l
                                              JOIN course_lecturer AS cl ON l.userID = cl.lecturer 
                                              JOIN courses AS c ON cl.course = c.courseID 
                                              WHERE l.username = ?");
        mysqli_stmt_bind_param($get_courses, 's', $_SESSION["username"]);
        mysqli_stmt_execute($get_courses);
        return mysqli_stmt_get_result($get_courses);

    } elseif (has_access_level($link, "lab helper")) {
        $get_courses = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_courses, "SELECT c.courseName FROM lab_helpers AS lh 
                                        JOIN user_login AS ul ON lh.userRef = ul.userID 
                                        JOIN courses AS c ON lh.course = c.courseID 
                                        WHERE username = ?");
        mysqli_stmt_bind_param($get_courses, 's', $_SESSION["username"]);
        mysqli_stmt_execute($get_courses);
        return mysqli_stmt_get_result($get_courses);
    }
    mysqli_close($link);
}





