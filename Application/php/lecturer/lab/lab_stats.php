<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 23:18
 */

require_once(dirname(__FILE__)."/../course/course_stats.php");
require_once(dirname(__FILE__)."/../../students/number_marked_students.php");


//echo lab_average_mark("Software Development 1", "df");
//echo currently_marked_average("Software Development 1", "df");

function lab_average_mark($link, $course, $lab)
{
    $studentCount = students_on_course_count($course);

    $totalMark = mysqli_stmt_init($link);
    mysqli_stmt_prepare($totalMark, "SELECT sum(mark) FROM lab_answers as la
                                         JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID
                                          JOIN labs as l ON lq.labRef = l.labID
                                          JOIN courses as c ON l.courseRef = c.courseID
                                          WHERE c.courseName = ? AND l.labName = ?");
    mysqli_stmt_bind_param($totalMark, "ss", $course, $lab);
    mysqli_stmt_execute($totalMark);
    $classMark = mysqli_stmt_get_result($totalMark)->fetch_row()[0];

    return number_format(($classMark/$studentCount),2,".","");
}


function currently_marked_average($link, $course, $lab)
{

    $currentlyMarkedMark = mysqli_stmt_init($link);
    mysqli_stmt_prepare($currentlyMarkedMark, "SELECT avg(totalMark) FROM (
                                      SELECT sum(mark) as totalMark FROM lab_answers AS la 
                                      JOIN lab_questions as lq ON la.labQuestionRef = lq.questionID  
                                      JOIN labs as l ON lq.labRef = l.labID
                                      JOIN courses as c ON l.courseRef = c.courseID
                                      WHERE c.courseName = ? AND l.labName = ? GROUP BY socRef)inner_query;");
    mysqli_stmt_bind_param($currentlyMarkedMark, "ss", $course, $lab);
    mysqli_stmt_execute($currentlyMarkedMark);
    $markedMark = mysqli_stmt_get_result($currentlyMarkedMark)->fetch_row()[0];

    return number_format(($markedMark),2,".","");
}


function get_lab_stat($course, $lab){

    $stats = [];
    include (dirname(__FILE__)."/../../core/connection.php");

    array_push($stats, currently_marked_average($link,$course,$lab));
    array_push($stats, lab_average_mark($link,$course,$lab));
    array_push($stats, currently_marked_students_count($link,$lab, $course));
    array_push($stats, students_on_course_count($course));

    mysqli_close($link);
    return $stats;
}