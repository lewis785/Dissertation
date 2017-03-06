<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 16:16
 */

function student_lab_answers($course, $lab, $username, $visibility) {
    include (dirname(__FILE__)."/../core/connection.php");


    $studentAnswers = mysqli_stmt_init($link);


    if($visibility === "true"){
        mysqli_stmt_prepare($studentAnswers,"SELECT lq.question, t.typeName, la.answerNumber , la.answerBoolean, la.answerText, la.mark, lq.maxMark
                                            FROM lab_answers as la 
                                            JOIN lab_questions as lq ON la.labQuestionRef = lq.questionID 
                                            JOIN question_types AS t ON lq.questionType = t.questionTypeID 
                                            JOIN labs as l ON lq.labRef = l.labID JOIN courses as c ON l.courseRef = c.courseID 
                                            JOIN students_on_courses as soc ON la.socRef = soc.socID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId 
                                            WHERE c.courseName = ? AND l.labName = ? AND ud.studentID = ?");
        mysqli_stmt_bind_param($studentAnswers, 'sss', $course, $lab, $username);
    }
    else {
        mysqli_stmt_prepare($studentAnswers, "SELECT lq.question, t.typeName, la.answerNumber , la.answerBoolean, la.answerText, la.mark, lq.maxMark
                                            FROM lab_answers AS la 
                                            JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID 
                                            JOIN question_types AS t ON lq.questionType = t.questionTypeID 
                                            JOIN labs AS l ON lq.labRef = l.labID JOIN courses AS c ON l.courseRef = c.courseID 
                                            JOIN students_on_courses AS soc ON la.socRef = soc.socID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId 
                                            WHERE c.courseName = ? AND l.labName = ? AND ud.studentID = ? AND lq.private = ? ");
        mysqli_stmt_bind_param($studentAnswers, 'ssss', $course, $lab, $username, $visibility);
    }

    mysqli_stmt_execute($studentAnswers);
    $result= mysqli_stmt_get_result($studentAnswers);

    $outputArray = [];
    while($output = $result->fetch_row())
        array_push($outputArray, $output);


    mysqli_close($link);
    return $outputArray;
}

function student_lab_answers_json($course, $lab, $username, $visibility)
{
    echo json_encode(array("answers"=>student_lab_answers($course, $lab, $username, $visibility)));
}