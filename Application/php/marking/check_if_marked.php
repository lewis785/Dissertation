<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 21:35
 */

function already_marked($studentID, $labQID)
{
    $link = $GLOBALS["link"];

    $check_already_marked = mysqli_stmt_init($link);
    mysqli_stmt_prepare($check_already_marked, "SELECT count(*) FROM lab_answers WHERE socRef = ? AND labQuestionRef = ?");
    mysqli_stmt_bind_param($check_already_marked, 'ii', $studentID, $labQID);
    mysqli_stmt_execute($check_already_marked);
    $result = mysqli_stmt_get_result($check_already_marked)->fetch_row();
    return $result[0] === 1;
}