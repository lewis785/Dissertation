<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 26/02/2017
 * Time: 21:42
 */


function lab_total_mark($link, $courseName, $labName)
{
    $labTotalMark = mysqli_stmt_init($link);
    mysqli_stmt_prepare($labTotalMark, "SELECT SUM(maxMark) FROM lab_questions as lq 
                                        JOIN labs AS l ON lq.labRef = l.labID
                                        JOIN courses AS c ON l.courseRef = c.courseID
                                        WHERE c.courseName = ? AND l.labName = ? ");
    mysqli_stmt_bind_param($labTotalMark, "ss", $courseName, $labName);

    if(mysqli_stmt_execute($labTotalMark))
    {
       $result = mysqli_stmt_get_result($labTotalMark)->fetch_row();
       return ($result[0]);
    }
}