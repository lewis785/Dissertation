<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 17:06
 */

require_once (dirname(__FILE__)."/../../core/classes/ConnectDB.php");

class CourseStats
{

    public function students_on_course_count($course)
    {
        $con = new ConnectDB();
        $numberOfStudent = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($numberOfStudent, "SELECT count(student) FROM students_on_courses AS soc
                                            JOIN courses AS c ON soc.course = c.courseID
                                             WHERE c.courseName = ?");
        mysqli_stmt_bind_param($numberOfStudent,"s", $course);
        mysqli_stmt_execute($numberOfStudent);
        $result =  mysqli_stmt_get_result($numberOfStudent)->fetch_row()[0];
        mysqli_close($con->link);
        return $result;
    }



}