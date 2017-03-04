<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 04/03/2017
 * Time: 17:09
 */


function student_lab_mark($link,$course,$lab)
{

    $studentLabMark = mysqli_stmt_init($link);
    mysqli_stmt_prepare($studentLabMark, "SELECT sum(mark) FROM lab_answers AS la 
                                        JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID 
                                        JOIN labs AS l ON lq.labRef = l.labID 
                                        JOIN students_on_courses AS soc ON la.socRef = soc.socID 
                                        JOIN courses AS c ON l.courseRef = c.courseID 
                                        JOIN user_login AS ul ON soc.student = ul.userID 
                                        WHERE username = ? AND courseName= ? AND l.labName = ?");
    mysqli_stmt_bind_param($studentLabMark, "sss",$_SESSION["username"], $course, $lab);
    mysqli_stmt_execute($studentLabMark);
    $result = mysqli_stmt_get_result($studentLabMark)->fetch_row();
    return $result[0];

}