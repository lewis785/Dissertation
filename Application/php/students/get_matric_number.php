<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 05/03/2017
 * Time: 17:13
 */


function get_student_matric($link, $username)
{
    $getMatric = mysqli_stmt_init($link);
    mysqli_stmt_prepare($getMatric, "SELECT ud.studentID FROM user_login as ul
                                              JOIN user_details  AS ud ON ul.userID = ud.detailsId 
                                              WHERE ul.username = ?");
    mysqli_stmt_bind_param($getMatric, 's', $username);
    mysqli_stmt_execute($getMatric);
    $result = mysqli_stmt_get_result($getMatric)->fetch_row();
    return $result[0];

}