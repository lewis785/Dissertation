<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 09/03/2017
 * Time: 16:20
 */

require_once(dirname(__FILE__) . "/../courses/Courses.php");
require_once(dirname(__FILE__) . "/../students/Student.php");


class CourseManager extends Courses
{
    private $student_functions;

    function __construct()
    {
        $this->student_functions = new Student();
    }


    public function addStudentToCourse($course, $student)
    {
        $success = false;
        if($this->has_access_level("lecturer"))
        {
            $con = new ConnectDB();
            $courseID = $this->get_course_id($course);
            $studentID = $this->student_functions->studentIDFromMatric($student);

            $success = $this->insertStudentOnCourse($con->link, $courseID, $studentID);
            mysqli_close($con->link);
        }
        return json_encode(array("success"=>$success));
    }


    public function removeStudentFromCourse($course, $student)
    {
        $success = false;
        if($this->has_access_level("lecturer")) {
            $con = new ConnectDB();
            $courseID = $this->get_course_id($course);
            $studentID = $this->student_functions->studentIDFromMatric($student);

            $success = $this->removeStudentOnCourse($con->link, $courseID, $studentID);
            mysqli_close($con->link);
        }
        return json_encode(array("success"=>$success));
    }









    //Checks if student is on course returns true if they are
    private function alreadyOnCourse($link, $courseID, $studentID)
    {
        $checkAlreadyPresent = mysqli_stmt_init($link);
        mysqli_stmt_prepare($checkAlreadyPresent, "SELECT count(*) FROM students_on_courses AS soc
                                                      WHERE soc.course = ? AND soc.student = ?");
        mysqli_stmt_bind_param($checkAlreadyPresent, "ii", $courseID, $studentID );
        mysqli_stmt_execute($checkAlreadyPresent);
        $result = mysqli_stmt_get_result($checkAlreadyPresent)->fetch_row();

        return $result[0] >= 1;

    }

    //Removes student from course
    private function removeStudentOnCourse($link, $course, $student)
    {
        $success = false;
        if($student !== "" && $course !== "") {
            $deleteStudentOnCourse = mysqli_stmt_init($link);
            mysqli_stmt_prepare($deleteStudentOnCourse, "DELETE FROM students_on_courses WHERE student = ? AND course = ?" );
            mysqli_stmt_bind_param($deleteStudentOnCourse, "ii", $student, $course);
            $success = mysqli_stmt_execute($deleteStudentOnCourse);
        }
        return $success;
    }


    //Adds student to course
    private function insertStudentOnCourse($link, $course, $student)
    {
        $success = false;
        if ($student !== "" && $course !== "") {
            if(!$this->alreadyOnCourse($link,$course, $student)) {

                $addStudentToCourse = mysqli_stmt_init($link);
                mysqli_stmt_prepare($addStudentToCourse, "INSERT INTO students_on_courses (student, course) VALUES (?,?)");
                mysqli_stmt_bind_param($addStudentToCourse, "ss", $student, $course);
                $success = mysqli_stmt_execute($addStudentToCourse);
            }
        }
        return $success;
    }


}

$add = new CourseManager();
//$add->removeStudentListFromCourse("Software Development 1", ["H00152598", "H00152678", "H00152565"]);
//$add->addStudentToCourse("Software Development 1", "H00152565");