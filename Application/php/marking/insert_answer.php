<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 21:09
 */

$insertAnswerQuery = 'INSERT INTO lab_answers (labQuestionRef, socRef, answerNumber, answerBoolean, answerText, mark) VALUES (?, ?, ?, ?, ?, ?)';
$insertAnswer = mysqli_stmt_init($link);
mysqli_stmt_prepare($insertAnswer, $insertAnswerQuery);
mysqli_stmt_bind_param($insertAnswer, 'iiissi', $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark);

$successful = mysqli_stmt_execute($insertAnswer);