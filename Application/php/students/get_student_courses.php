<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:55
 */


function get_student_courses($student, $filter = null)
{
    include (dirname(__FILE__)."/../core/connection.php");


    $courses = mysqli_stmt_init($link);

    if ($filter == null)
    {
        mysqli_stmt_prepare($courses,"SELECT c.courseName FROM students_on_courses as soc
                                  JOIN user_details AS ud ON soc.student = ud.detailsId
                                  JOIN courses AS c ON soc.course = c.courseID
                                  WHERE ud.studentId = ?" );
        mysqli_stmt_bind_param($courses,"s", $student);
    }
    else{
        mysqli_stmt_prepare($courses,"SELECT c.courseName FROM students_on_courses as soc
                                  JOIN user_details AS ud ON soc.student = ud.detailsId
                                  JOIN courses AS c ON soc.course = c.courseID
                                  WHERE ud.studentId = ? AND c.courseName = ?" );
        mysqli_stmt_bind_param($courses,"ss", $student, $filter);
    }


    mysqli_stmt_execute($courses);
    $result = mysqli_stmt_get_result($courses);
    mysqli_close($link);

    $output = [];
    while($course = $result->fetch_row())
        array_push($output, $course[0]);

    return $output;
}