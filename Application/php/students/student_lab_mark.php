<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 16:48
 */

//Returns the mark a student received for a lab
if (!function_exists("lab_mark_for_student")) {
    function lab_mark_for_student($link, $matricID, $courseName, $labName)
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