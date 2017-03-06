<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 05/03/2017
 * Time: 23:31
 */

function currently_marked_students_count($link, $lab, $course)
{
    $studentCount = mysqli_stmt_init($link);
    mysqli_stmt_prepare($studentCount, "SELECT count(distinct(socRef)) FROM lab_answers AS la 
                                        JOIN lab_questions as lq ON la.labQuestionRef = lq.questionID 
                                        JOIN labs as l on lq.labRef = l.labID 
                                        JOIN courses AS c ON l.courseRef = c.courseID 
                                        WHERE c.courseName = ? AND l.labName = ?");
    mysqli_stmt_bind_param($studentCount, "ss", $course, $lab);
    mysqli_stmt_execute($studentCount);
    $numOfStudents = mysqli_stmt_get_result($studentCount)->fetch_row();
    return $numOfStudents[0];
}