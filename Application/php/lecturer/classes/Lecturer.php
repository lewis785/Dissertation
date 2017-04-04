<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 11/03/2017
 * Time: 01:40
 */


require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once(dirname(__FILE__) . "/../../core/classes/Security.php");
require_once(dirname(__FILE__) . "/../../courses/classes/Courses.php");
require_once(dirname(__FILE__) . "/../../users/classes/UserManager.php");

class Lecturer
{
    private $_Security;
    private $_Courses;
    private $_User;

    function __construct()
    {
        $this->_Security = new Security();
        $this->_Courses = new Courses();
        $this->_User = new UserManager();
    }

    public function getAllLecturers()
    {
        $con = new ConnectDB();
        $allLecturers = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($allLecturers, "SELECT ud.firstname, ud.surname, ul.username FROM user_details AS  ud
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
        mysqli_stmt_prepare($lecturerCourse, "SELECT ud.firstname, ud.surname, ul.username FROM user_details AS  ud
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
            mysqli_stmt_prepare($noCourseLecturer, "SELECT ud.firstname, ud.surname, ul.username FROM user_details AS ud 
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
            mysqli_stmt_prepare($noCourseLecturer, "SELECT ud.firstname, ud.surname, ul.username FROM user_details AS ud 
                                            JOIN user_login AS ul ON ud.detailsId = ul.userID 
                                            JOIN user_access AS ua ON ul.accessLevel = ua.access_id 
                                            WHERE (ua.access_name = 'lecturer')
                                            AND (CONCAT(ud.firstname, ' ', ud.surname) LIKE  ?)
                                            AND ud.detailsId NOT IN (SELECT cl.lecturer FROM course_lecturer AS cl
                                            JOIN courses AS c ON cl.course = c.courseID
                                            JOIN user_details AS ud ON cl.lecturer = ud.detailsId
                                            WHERE c.courseName = ?)");
            mysqli_stmt_bind_param($noCourseLecturer, "ss", $filter, $course);
        }
        mysqli_stmt_execute($noCourseLecturer);
        $result = mysqli_stmt_get_result($noCourseLecturer);

        $output_array = [];
        while($lecturer = $result->fetch_row())
            array_push($output_array,$lecturer);
        return $output_array;
    }

    public function addLectureToCourse($course, $lecturer)
    {
        $success= false;
        if($this->_Security->hasAccessLevel("admin"))
        {
            $con = new ConnectDB();
            if(!$this->alreadyLecturer($con->link, $course, $lecturer))
            {
                $courseID = $this->_Courses->getCourseId($course);
                $userID = $this->_User->getIdFromUsername($lecturer);

                $addLecturerToCourse = mysqli_stmt_init($con->link);
                mysqli_stmt_prepare($addLecturerToCourse,"INSERT INTO course_lecturer (course, lecturer) VALUES (?, ?)");
                mysqli_stmt_bind_param($addLecturerToCourse, "ss", $courseID, $userID);
                $success = mysqli_stmt_execute($addLecturerToCourse);

            }
            mysqli_close($con->link);
        }
        return json_encode(array("success"=>$success));
    }

    public function removeLectureFromCourse($course, $lecturer)
    {
        $success= false;
        if($this->_Security->hasAccessLevel("admin"))
        {
            $con = new ConnectDB();
                $courseID = $this->_Courses->getCourseId($course);
                $userID = $this->_User->getIdFromUsername($lecturer);

                $addLecturerToCourse = mysqli_stmt_init($con->link);
                mysqli_stmt_prepare($addLecturerToCourse,"DELETE FROM course_lecturer WHERE course = ? AND lecturer = ?");
                mysqli_stmt_bind_param($addLecturerToCourse, "ss", $courseID, $userID);
                $success = mysqli_stmt_execute($addLecturerToCourse);

            mysqli_close($con->link);
        }
        return json_encode(array("success"=>$success));

    }


    private function alreadyLecturer($link, $course,$lecturer)
    {
        $lecturerOfCourses = mysqli_stmt_init($link);
        mysqli_stmt_prepare($lecturerOfCourses,"SELECT count(*) FROM course_lecturer AS cl
                                                JOIN  courses AS c ON cl.course = c.courseID
                                                JOIN user_login AS ul ON cl.lecturer = ul.userID
                                                WHERE ul.username = ? AND c.courseName = ?");
        mysqli_stmt_bind_param($lecturerOfCourses, "ss", $lecturer, $course );
        mysqli_stmt_execute($lecturerOfCourses);
        $result = mysqli_stmt_get_result($lecturerOfCourses)->fetch_row()[0];
        return $result === 1;
    }

}

//$lecturer = new Lecturer();
//print_r($lecturer->getLecturersNotOfCourse("Software Development 1", "gray"));