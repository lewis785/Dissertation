<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 02:40
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once(dirname(__FILE__) . "/../../labs/classes/Lab.php");


class Student
{
    public $lab;

    function __construct()
    {
        $this->lab = new Lab();

    }


    public function getAllStudents()
    {
        $con = new ConnectDB();

        $get_studentsID = mysqli_stmt_init($con->link);
            mysqli_stmt_prepare($get_studentsID, "SELECT ud.firstname, ud.surname, ud.studentID FROM user_details AS ud 
                                                JOIN user_login AS ul ON ud.detailsId = ul.userID 
                                                JOIN user_access AS ua ON ul.accessLevel = ua.access_id 
                                                WHERE ua.access_name = 'student' OR ua.access_name='lab helper' 
                                                ORDER BY surname, firstname");
        mysqli_stmt_execute($get_studentsID);
        $result = mysqli_stmt_get_result($get_studentsID);

        $output_array = [];
        while($student = $result->fetch_row())
            array_push($output_array, $student);

        mysqli_close($con->link);
        return $output_array;
    }

    public function studentsOnCourse($course)
    {
        $con = new ConnectDB();

        $studentOnCourse = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($studentOnCourse,"SELECT ud.firstname, ud.surname, ud.studentID FROM user_details AS ud
                                              JOIN students_on_courses AS soc ON ud.detailsId = soc.student
                                              JOIN courses AS c ON soc.course = c.courseID
                                              WHERE c.courseName = ?
                                              ORDER BY ud.surname, ud.firstname");
        mysqli_stmt_bind_param($studentOnCourse, "s", $course);
        mysqli_stmt_execute($studentOnCourse);
        $results = mysqli_stmt_get_result($studentOnCourse);

        $output_array = [];
        while($student = $results->fetch_row())
            array_push($output_array, $student);

        mysqli_close($con->link);
        return $output_array;
    }

    public function studentNotOnCourse($course, $filter = "")
    {
        $con = new ConnectDB();

        $notOnCourse = mysqli_stmt_init($con->link);
        if($filter === "") {
            mysqli_stmt_prepare($notOnCourse, "SELECT ud.firstname, ud.surname, ud.studentID FROM user_details AS ud 
                                            JOIN user_login AS ul ON ud.detailsId = ul.userID 
                                            JOIN user_access AS ua ON ul.accessLevel = ua.access_id 
                                            WHERE (ua.access_name = 'student' OR ua.access_name = 'lab helper') 
                                            AND ud.detailsId NOT IN (SELECT soc.student FROM students_on_courses AS soc
                                            JOIN courses AS c ON soc.course = c.courseID 
                                            WHERE c.courseName = ?)
                                            ORDER BY ud.surname, ud.firstname");
            mysqli_stmt_bind_param($notOnCourse, "s", $course);
        }
        else{
            $filter = "%".$filter."%";
            mysqli_stmt_prepare($notOnCourse, "SELECT ud.firstname, ud.surname, ud.studentID FROM user_details AS ud 
                                            JOIN user_login AS ul ON ud.detailsId = ul.userID 
                                            JOIN user_access AS ua ON ul.accessLevel = ua.access_id 
                                            WHERE (ua.access_name = 'student' OR ua.access_name = 'lab helper')
                                            AND (CONCAT(ud.firstname, ' ', ud.surname) LIKE  ? OR ud.studentID LIKE ?)
                                            AND ud.detailsId NOT IN (SELECT soc.student FROM students_on_courses AS soc
                                            JOIN courses AS c ON soc.course = c.courseID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId
                                            WHERE c.courseName = ?)
                                            ORDER BY ud.surname, ud.firstname");
            mysqli_stmt_bind_param($notOnCourse, "sss", $filter, $filter, $course);
        }
        mysqli_stmt_execute($notOnCourse);
        $results = mysqli_stmt_get_result($notOnCourse);

        $output_array = [];
        while($student = $results->fetch_row())
            array_push($output_array, $student);

        mysqli_close($con->link);
        return $output_array;
    }


    public function studentIDFromMatric($student)
    {
        $con = new ConnectDB();

        $get_studentsID = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_studentsID, "SELECT ud.detailsId FROM user_details as ud  
                                              WHERE ud.studentID = ?");
        mysqli_stmt_bind_param($get_studentsID, 's', $student);
        mysqli_stmt_execute($get_studentsID);
        $result = mysqli_stmt_get_result($get_studentsID)->fetch_row();

        mysqli_close($con->link);
        return $result[0];
    }


    public function getStudentMatric($link, $username)
    {
        $getMatric = mysqli_stmt_init($link);
        mysqli_stmt_prepare($getMatric, "SELECT ud.studentID FROM user_login as ul
                                              JOIN user_details  AS ud ON ul.userID = ud.detailsId 
                                              WHERE ul.username = ?");
        mysqli_stmt_bind_param($getMatric, 's', $username);
        mysqli_stmt_execute($getMatric);
        $result = mysqli_stmt_get_result($getMatric)->fetch_row();
        return $result[0];

    }

    public function getStudentCourses($student, $filter = null)
    {
        $con = new ConnectDB();


        $courses = mysqli_stmt_init($con->link);

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
        mysqli_close($con->link);

        $output = [];
        while($course = $result->fetch_row())
            array_push($output, $course[0]);

        return $output;
    }



    public function hasFullMarks($link, $student, $courseName, $labName)
    {
        $maxMark = $this->lab->labTotalMark($courseName,$labName);
        $studentMark = $this->labMarkForStudent($link, $student, $courseName, $labName);
        return $studentMark == $maxMark;
    }


    public function has_no_marks($link, $student,$courseName, $labName)
    {
        $studentMark = $this->labMarkForStudent($link, $student, $courseName, $labName);
        return $studentMark == 0;
    }


    public function labMarkForStudent($link, $matricID, $courseName, $labName)
    {
        $labMark = mysqli_stmt_init($link);
        mysqli_stmt_prepare($labMark, "SELECT sum(mark) FROM lab_answers AS la
                              JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID
                              JOIN labs AS l ON lq.labRef = l.labID
                              JOIN courses AS c ON l.courseRef = c.courseID
                              JOIN students_on_courses AS soc ON la.socRef = soc.socID
                              JOIN user_details AS ud ON soc.student = ud.detailsId 
                              WHERE ud.studentID = ? AND c.courseName = ? AND l.labName = ?");
        mysqli_stmt_bind_param($labMark, "sss", $matricID, $courseName, $labName);

        mysqli_stmt_execute($labMark);
        $result = mysqli_stmt_get_result($labMark)->fetch_row();

        return $result[0];
    }


    public function studentLabAnswers($course, $lab, $username, $visibility) {

        $con = new ConnectDB();

        $studentAnswers = mysqli_stmt_init($con->link);

        if($visibility === "true"){
            mysqli_stmt_prepare($studentAnswers,"SELECT lq.question, t.typeName, la.answerNumber , la.answerBoolean, la.answerText, la.mark, lq.maxMark
                                            FROM lab_answers as la 
                                            JOIN lab_questions as lq ON la.labQuestionRef = lq.questionID 
                                            JOIN question_types AS t ON lq.questionType = t.questionTypeID 
                                            JOIN labs as l ON lq.labRef = l.labID JOIN courses as c ON l.courseRef = c.courseID 
                                            JOIN students_on_courses as soc ON la.socRef = soc.socID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId 
                                            WHERE c.courseName = ? AND l.labName = ? AND ud.studentID = ?");
            mysqli_stmt_bind_param($studentAnswers, 'sss', $course, $lab, $username);
        }
        else {
            mysqli_stmt_prepare($studentAnswers, "SELECT lq.question, t.typeName, la.answerNumber , la.answerBoolean, la.answerText, la.mark, lq.maxMark
                                            FROM lab_answers AS la 
                                            JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID 
                                            JOIN question_types AS t ON lq.questionType = t.questionTypeID 
                                            JOIN labs AS l ON lq.labRef = l.labID JOIN courses AS c ON l.courseRef = c.courseID 
                                            JOIN students_on_courses AS soc ON la.socRef = soc.socID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId 
                                            WHERE c.courseName = ? AND l.labName = ? AND ud.studentID = ? AND lq.private = ? ");
            mysqli_stmt_bind_param($studentAnswers, 'ssss', $course, $lab, $username, $visibility);
        }

        mysqli_stmt_execute($studentAnswers);
        $result= mysqli_stmt_get_result($studentAnswers);

        $outputArray = [];
        while($output = $result->fetch_row())
            array_push($outputArray, $output);

        mysqli_close($con->link);
        return $outputArray;
    }


    public function student_lab_answers_json($course, $lab, $username, $visibility)
    {
        return json_encode(array("answers"=>$this->studentLabAnswers($course, $lab, $username, $visibility)));
    }



}