<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 16:19
 */

function get_all_question_ids($link,$courseName, $labName)
{
    $get_lab_question_ids = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_lab_question_ids, "SELECT questionID FROM lab_questions AS lq 
                                                    JOIN labs AS l ON lq.labRef = l.labID
                                                    JOIN courses AS c ON l.courseRef = c.courseID
                                                    WHERE c.courseName = ? AND l.labName = ?");
    mysqli_stmt_bind_param($get_lab_question_ids, 'ss', $courseName, $labName);
    mysqli_stmt_execute($get_lab_question_ids);
    $result= mysqli_stmt_get_result($get_lab_question_ids)->fetch_array();
//    $outputArray = [];
//    while($question = $result->fetch_row())
//        array_push($outputArray,$question[0]);
    return $result;
}