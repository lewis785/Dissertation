<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 21:41
 */

function get_studentID($link, $student)
{
    $get_studentsID = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_studentsID, "SELECT s.socID FROM students_on_courses AS s 
                                          JOIN user_details as d ON s.student = d.detailsID 
                                          WHERE d.studentID = ?");
    mysqli_stmt_bind_param($get_studentsID, 's', $student);
    mysqli_stmt_execute($get_studentsID);
    $result = mysqli_stmt_get_result($get_studentsID)->fetch_row();

    return $result[0];
}
