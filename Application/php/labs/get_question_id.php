<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 22:20
 */


function get_questionID($link, $labID, $questionNum)
{
    $get_questionID = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_questionID, "SELECT questionID FROM lab_questions WHERE labRef = ? AND questionNumber = ?");
    mysqli_stmt_bind_param($get_questionID, 'ii', $labID, $questionNum);
    mysqli_stmt_execute($get_questionID);
    $result = mysqli_stmt_get_result($get_questionID)->fetch_row();
    return $result[0];
}