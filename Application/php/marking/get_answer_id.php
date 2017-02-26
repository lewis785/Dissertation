<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 21:20
 */


$get_answerID = mysqli_stmt_init($link);
mysqli_stmt_prepare($get_answerID, "SELECT answerID FROM lab_answers WHERE labQuestionRef = ? AND socRef = ?");
mysqli_stmt_bind_param($get_answerID, 'ii', $questionID, $studentID);
mysqli_stmt_execute($get_answerID);
$answerID = mysqli_stmt_get_result($get_answerID)->fetch_row();