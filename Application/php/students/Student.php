<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 02:40
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../labs/Lab.php");


class Student
{
    private $lab;

    function __construct()
    {
        $this->lab = new Lab();
    }

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

    public function has_full_marks($link, $student,$courseName, $labName)
    {
        $maxMark = $this->lab->lab_total_mark($courseName,$labName);
        $studentMark = $this->lab_mark_for_student($link, $student, $courseName, $labName);
        return $studentMark == $maxMark;
    }

    public function has_no_marks($link, $student,$courseName, $labName)
    {
        $studentMark = $this->lab_mark_for_student($link, $student, $courseName, $labName);
        return $studentMark == 0;
    }

    public function lab_mark_for_student($link, $matricID, $courseName, $labName)
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

}