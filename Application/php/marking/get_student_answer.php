<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 25/02/2017
 * Time: 19:12
 */

include(dirname(__FILE__)."/../core/connection.php");



if($_SESSION["MARKING_COURSE"] && $_SESSION["MARKING_LAB"] && $_SESSION["MARKING_STUDENT"]) {
    require (dirname(__FILE__)."/../labs/get_lab_id.php");


    if ($_SESSION["MARKING_COURSE"] !== "" && $_SESSION["MARKING_LAB"] !== "" && $_SESSION["MARKING_STUDENT"] !== "") {
        $student = $_SESSION["MARKING_STUDENT"];

        $get_lab_question_ids = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_lab_question_ids, "SELECT questionID FROM lab_questions AS lq 
                                                    JOIN labs AS l ON lq.labRef = l.labID
                                                    JOIN courses AS c ON l.courseRef = c.courseID
                                                    WHERE c.courseName = ? AND l.labName = ?");
        mysqli_stmt_bind_param($get_lab_question_ids, 'ss', $_SESSION["MARKING_COURSE"], $_SESSION["MARKING_LAB"]);
        mysqli_stmt_execute($get_lab_question_ids);
        $result= mysqli_stmt_get_result($get_lab_question_ids);


        $get_answers = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_answers, "SELECT la.answerNumber, la.answerBoolean, la.answerText FROM lab_answers AS la 
                                            JOIN students_on_courses AS soc ON la.socRef = soc.socID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId 
                                            WHERE labQuestionRef = ? AND ud.studentID = ? ");

        while ( $question = $result->fetch_row()){

            echo ($question[0] . $student);
            mysqli_stmt_bind_param($get_answers, 'is', $question[0], $student );
            mysqli_stmt_execute($get_answers);
            $answerID = mysqli_stmt_get_result($get_answers)->fetch_row();
            print_r($answerID);
        }
    }
}

mysqli_close($link);