<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 02:40
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");

class Student
{

    public function get_studentID($student)
    {
        $con = new ConnectDB();

        $get_studentsID = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_studentsID, "SELECT s.socID FROM students_on_courses AS s 
                                          JOIN user_details as d ON s.student = d.detailsID 
                                          WHERE d.studentID = ?");
        mysqli_stmt_bind_param($get_studentsID, 's', $student);
        mysqli_stmt_execute($get_studentsID);
        $result = mysqli_stmt_get_result($get_studentsID)->fetch_row();

        mysqli_close($con->link);
        return $result[0];
    }

}