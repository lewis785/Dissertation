<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 16:16
 */

include(dirname(__FILE__) . "/../core/check_access_level.php");



    //Returns true if user is a lecturer of the course
if (!function_exists("is_lecturer_of_course")) {
    function is_lecturer_of_course($link, $course)
    {

        $check_if_course_lecturer = mysqli_stmt_init($link);
        mysqli_stmt_prepare($check_if_course_lecturer, "SELECT count(*) FROM user_login AS l
                                              JOIN course_lecturer AS cl ON l.userID = cl.lecturer 
                                              JOIN courses AS c ON cl.course = c.courseID 
                                              WHERE l.username = ? AND c.courseName = ?");
        mysqli_stmt_bind_param($check_if_course_lecturer, 'ss', $_SESSION["username"], $course);
        mysqli_stmt_execute($check_if_course_lecturer);
        $result = mysqli_stmt_get_result($check_if_course_lecturer);
        $lecturerCount = $result->fetch_row();
        return $lecturerCount[0] === 1;
    }
}

    //Returns true if user is a lab helper of the course
if (!function_exists("is_lab_helper_of_course")) {
    function is_lab_helper_of_course($link, $course)
    {

        $check_if_lab_helper = mysqli_stmt_init($link);                                             //Init prepared statement
        mysqli_stmt_prepare($check_if_lab_helper, "SELECT count(*) FROM lab_helpers AS lh
                                                  JOIN courses AS c ON c.courseID = lh.course 
                                                  JOIN user_login AS ul ON lh.userRef = ul.userID 
                                                  WHERE ul.username = ? AND c.courseName = ?");
        mysqli_stmt_bind_param($check_if_lab_helper, 'ss', $_SESSION["username"], $course);         //Provides session and course as inputs for query
        mysqli_stmt_execute($check_if_lab_helper);                                                  //Executes the prepared statement
        $result = mysqli_stmt_get_result($check_if_lab_helper);                                     //Get Results of Query
        $helperCount = $result->fetch_row();                                                        //Fetches first row of result
        return $helperCount[0] === 1;                                                               //Returns true if count equals 1
    }
}

    //Checks if user is allowed to mark course returns true if they are
if (!function_exists("can_mark_course")) {
    function can_mark_course($link, $course)
    {
        if (has_access_level($link, "lecturer")) {                                                  //Checks if user is a lecturer
            return is_lecturer_of_course($link, $course);                                           //Returns true if user is lecturer of specified course
        } elseif (has_access_level($link, "lab helper")) {                                          //Checks if user is a lab helper
            return is_lab_helper_of_course($link, $course);                                          //Returns true if user is lab helper of specified course
        }
    }
}