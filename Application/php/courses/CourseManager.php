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
        if($this->$this->has_access_level("lecturer"))
        {
            $con = new ConnectDB();
            $courseID = $this->get_course_id($course);
            $studentID = $this->student_functions->get_studentID($student);

            $this->insertStudentOnCourse($con->link, $courseID, $studentID);
            mysqli_close($con->link);
        }
    }

    public function addStudentListToCourse($course, $student_array)
    {
        if($this->$this->has_access_level("lecturer")) {
            $con = new ConnectDB();
            $courseID = $this->get_course_id($course);

            foreach ($student_array as $student) {
                $studentID = $this->student_functions->get_studentID($student);
                $this->insertStudentOnCourse($con->link, $courseID, $studentID);
            }

            mysqli_close($con->link);
        }
    }

    public function addCoursesListToStudent($courses, $student)
    {
        if($this->$this->has_access_level("lecturer")) {
            $con = new ConnectDB();
            $studentID = $this->student_functions->get_studentID($student);

            if ($studentID !== "") {
                foreach ($courses as $course) {
                    $courseID = $this->get_course_id($course);
                    $this->insertStudentOnCourse($con->link, $courseID, $studentID);
                }
            }
        }
    }


    public function removeStudentFromCourse($course, $student)
    {
        if($this->$this->has_access_level("lecturer")) {
            $con = new ConnectDB();
            $courseID = $this->get_course_id($course);
            $studentID = $this->student_functions->get_studentID($student);

            $this->removeStudentOnCourse($con->link, $courseID, $studentID);
            mysqli_close($con->link);
        }
    }

    public function removeStudentListFromCourse($course, $students)
    {
        if($this->$this->has_access_level("lecturer")) {
            $con = new ConnectDB();
            $courseID = $this->get_course_id($course);

            if ($courseID !== "") {
                foreach ($students as $student) {
                    $studentID = $this->student_functions->get_studentID($student);
                    $this->removeStudentOnCourse($con->link, $courseID, $studentID);
                }
            }
            mysqli_close($con->link);
        }
    }








    //Checks if student is on course returns true if they are
    private function alreadyOnCourse($link, $courseID, $studentID)
    {
        echo " course : ".$courseID ." student : ". $studentID;

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
        echo $course, $student;
        if($student !== "" && $course !== "") {
            $deleteStudentOnCourse = mysqli_stmt_init($link);
            mysqli_stmt_prepare($deleteStudentOnCourse, "DELETE FROM students_on_courses WHERE student = ? AND course = ?" );
            mysqli_stmt_bind_param($deleteStudentOnCourse, "ii", $student, $course);
            mysqli_stmt_execute($deleteStudentOnCourse);
        }
    }


    //Adds student to course
    private function insertStudentOnCourse($link, $course, $student)
    {

        if ($student !== "" && $course !== "") {
            if(!$this->alreadyOnCourse($link,$course, $student)) {

                $addStudentToCourse = mysqli_stmt_init($link);
                mysqli_stmt_prepare($addStudentToCourse, "INSERT INTO students_on_courses (student, course) VALUES (?,?)");
                mysqli_stmt_bind_param($addStudentToCourse, "ss", $student, $course);
                mysqli_stmt_execute($addStudentToCourse);
            }
            else
                echo "already on course";
        }
        echo $student;
    }


}

$add = new CourseManager();
//$add->removeStudentListFromCourse("Software Development 1", ["H00152598", "H00152678", "H00152565"]);
//$add->removeStudentFromCourse("Software Development 1", "H00152565");