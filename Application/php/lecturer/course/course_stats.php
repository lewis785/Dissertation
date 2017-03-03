<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 23:19
 */


function students_on_course_count($course)
{
    include (dirname(__FILE__)."/../../core/connection.php");
    $numberOfStudent = mysqli_stmt_init($link);
    mysqli_stmt_prepare($numberOfStudent, "SELECT count(student) FROM students_on_courses AS soc
                                            JOIN courses AS c ON soc.course = c.courseID
                                             WHERE c.courseName = ?");
    mysqli_stmt_bind_param($numberOfStudent,"s", $course);
    mysqli_stmt_execute($numberOfStudent);
    $result =  mysqli_stmt_get_result($numberOfStudent)->fetch_row()[0];
    mysqli_close($link);
    return $result;
}