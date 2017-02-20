<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 18/02/2017
 * Time: 19:51
 */

//Returns the ID number of the course passed into it
function get_course_id($course)
{
    $link = $GLOBALS["link"];
    $getCourseIDQuery = 'SELECT courseID FROM courses WHERE courseName = ?';
    $getCourseID = mysqli_stmt_init($link);
    mysqli_stmt_prepare($getCourseID, $getCourseIDQuery);
    mysqli_stmt_bind_param($getCourseID, 's',$course);
    mysqli_stmt_execute($getCourseID);
    $result = mysqli_stmt_get_result($getCourseID)->fetch_row();
    return $result[0];
}