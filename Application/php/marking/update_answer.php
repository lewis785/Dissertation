<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 21:11
 */

require "get_answer_id.php";

$updateAnswerQuery = 'UPDATE lab_answers SET answerNumber = ?, answerBoolean = ?, answerText = ?, mark = ? WHERE answerID = ?';
$updateAnswer = mysqli_stmt_init($link);
mysqli_stmt_prepare($updateAnswer, $updateAnswerQuery);
mysqli_stmt_bind_param($updateAnswer, 'issii', $ansNum, $ansBool, $ansText, $mark, $answerID[0]);

$successful = mysqli_stmt_execute($updateAnswer);