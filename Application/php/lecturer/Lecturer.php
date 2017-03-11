<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 11/03/2017
 * Time: 01:40
 */


require_once (dirname(__FILE__)."/../core/ConnectDB.php");

class Lecturer
{

    public function getAllLecturers()
    {
        $con = new ConnectDB();
        $allLecturers = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($allLecturers, "SELECT ud.firstname, ud.surname FROM user_details AS  ud
                                              JOIN user_login AS ul ON ud.detailsId = ul.userID
                                              JOIN user_access AS ua ON ul.accessLevel = ua.access_id
                                              JOIN course_lecturer AS cl ON ud.detailsId = cl.lecturer
                                              JOIN courses AS c ON cl.course = c.courseID
                                              WHERE ua.access_name = 'lecturer'");
        mysqli_stmt_execute($allLecturers);
        $result = mysqli_stmt_get_result($allLecturers);

        $output_array = [];
        while($lecturer = $result->fetch_row())
            array_push($output_array, $lecturer);

        return $output_array;

    }

    public function getLecturersOfCourse($course)
    {
        $con = new ConnectDB();
        $lecturerCourse = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($lecturerCourse, "SELECT ud.firstname, ud.surname FROM user_details AS  ud
                                              JOIN user_login AS ul ON ud.detailsId = ul.userID
                                              JOIN user_access AS ua ON ul.accessLevel = ua.access_id
                                              JOIN course_lecturer AS cl ON ud.detailsId = cl.lecturer
                                              JOIN courses AS c ON cl.course = c.courseID
                                              WHERE ua.access_name = 'lecturer' AND c.courseName = ?");
        mysqli_stmt_bind_param($lecturerCourse, "s", $course);
        mysqli_stmt_execute($lecturerCourse);
        $result = mysqli_stmt_get_result($lecturerCourse);

        $output_array = [];
        while($lecturer = $result->fetch_row())
            array_push($output_array, $lecturer);

        return $output_array;
    }

    public function getLecturersNotOfCourse($course, $filter="")
    {
        $con = new ConnectDB();

        $noCourseLecturer = mysqli_stmt_init($con->link);
        if($filter === "") {
            mysqli_stmt_prepare($noCourseLecturer, "SELECT ud.firstname, ud.surname FROM user_details AS ud 
                                            JOIN user_login AS ul ON ud.detailsId = ul.userID 
                                            JOIN user_access AS ua ON ul.accessLevel = ua.access_id 
                                            WHERE (ua.access_name = 'lecturer') 
                                            AND ud.detailsId NOT IN (SELECT cl.lecturer FROM course_lecturer AS cl
                                            JOIN courses AS c ON cl.course = c.courseID 
                                            WHERE c.courseName = ?)");
            mysqli_stmt_bind_param($noCourseLecturer, "s", $course);
        }
        else{
            $filter = "%".$filter."%";
            mysqli_stmt_prepare($noCourseLecturer, "SELECT ud.firstname, ud.surname FROM user_details AS ud 
                                            JOIN user_login AS ul ON ud.detailsId = ul.userID 
                                            JOIN user_access AS ua ON ul.accessLevel = ua.access_id 
                                            WHERE (ua.access_name = 'lecturer')
                                            AND (CONCAT(ud.firstname, ' ', ud.surname) LIKE  ?)
                                            AND ud.detailsId NOT IN (SELECT cl.lecturer FROM course_lecturer AS cl
                                            JOIN courses AS c ON soc.course = c.courseID 
                                            JOIN user_details AS ud ON cl.student = ud.detailsId
                                            WHERE c.courseName = ?)");
            mysqli_stmt_bind_param($noCourseLecturer, "sss", $filter, $filter, $course);
        }
        mysqli_stmt_execute($noCourseLecturer);
        $result = mysqli_stmt_get_result($noCourseLecturer);

        $output_array = [];
        while($lecturer = $result->fetch_row())
            array_push($output_array,$lecturer);
        return $output_array;
    }
}

//$lecturer = new Lecturer();
//print_r($lecturer->getAllLecturers("Software Development 1"));