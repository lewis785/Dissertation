<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 16:16
 */

include(dirname(__FILE__) . "/../core/check_access_level.php");


function can_mark_course($link, $course)
{
    if(has_access_level($link,"lecturer")){
        $check_if_course_lecturer = mysqli_stmt_init($link);
        mysqli_stmt_prepare($check_if_course_lecturer, "SELECT count(*) FROM user_login as l
                                              JOIN course_lecturer AS cl ON l.userID = cl.lecturer 
                                              JOIN courses AS c ON cl.course = c.courseID 
                                              WHERE l.username = ? AND c.courseName = ?");
        mysqli_stmt_bind_param($check_if_course_lecturer, 'ss', $_SESSION["username"], $course);
        mysqli_stmt_execute($check_if_course_lecturer);
        $result = mysqli_stmt_get_result($check_if_course_lecturer);
        $lecturerCount = $result->fetch_row();
        return $lecturerCount[0] === 1;

    }
    elseif(has_access_level($link, "lab helper")) {
        $check_if_lab_helper = mysqli_stmt_init($link);
        mysqli_stmt_prepare($check_if_lab_helper, "SELECT count(*) FROM lab_helpers AS lh 
                                                  JOIN courses AS c ON c.courseID = lh.course 
                                                  JOIN user_login AS ul ON lh.userRef = ul.userID 
                                                  WHERE ul.username = ? AND c.courseName = ?");
        mysqli_stmt_bind_param($check_if_lab_helper, 'ss', $_SESSION["username"], $course);
        mysqli_stmt_execute($check_if_lab_helper);
        $result = mysqli_stmt_get_result($check_if_lab_helper);
        $helperCount = $result->fetch_row();
        return $helperCount[0] === 1;
    }
}