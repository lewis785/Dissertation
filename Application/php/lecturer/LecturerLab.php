<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 12:48
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../students/Student.php");
require_once (dirname(__FILE__)."/../marking/Marking.php");

class LecturerLab extends Lab
{

    private $student;
    private $marking;

    function __construct()
    {
        $this->student = new Student();
        $this->marking = new Marking();
    }


    public function checkLabName($course, $lab)
    {

        $con = new ConnectDB();

        $checkIfNameExists = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($checkIfNameExists, "SELECT count(*) FROM labs AS l 
                                          JOIN courses AS c ON l.courseRef = c.courseID
                                           WHERE c.courseName = ? AND l.labName = ?");
        mysqli_stmt_bind_param($checkIfNameExists, "ss", $course, $lab);
        mysqli_stmt_execute($checkIfNameExists);

        $result = mysqli_stmt_get_result($checkIfNameExists)->fetch_row();

        mysqli_close($con->link);
        return json_encode(array("exists" => ($result[0] === 1)));
    }


    public function get_student_result($course, $lab, $username, $visibility)
    {
        $con = new ConnectDB();

        $stats = "";
        $answers = $this->get_student_answers($course, $lab, $username, $visibility);

        if ($answers[1])
            $stats = $this->get_student_stats($con->link, $course, $lab, $username);

        mysqli_close($con->link);
        return json_encode(array("stats" => $stats, "answers" => $answers[0]));

    }

    private function get_student_stats($link, $course, $lab, $username)
    {
        $statsDiv = "";
        include(dirname(__FILE__) . "/../core/connection.php");
        $maxMark = $this->lab_total_mark($course, $lab);
        $studentMark = $this->student->lab_mark_for_student($link, $username, $course, $lab);
        $percentage = number_format((($studentMark / $maxMark) * 100), 2, ".", "") . "%";

        $statsDiv .= "<div class='col-md-4'></div>";
        $statsDiv .= "<div class='col-md-4'>Mark: $studentMark / $maxMark</div>";
        $statsDiv .= "<div class='col-md-4'>Percentage: $percentage </div>";

        return $statsDiv;
    }

    private function get_student_answers($course, $lab, $username, $visibility)
    {
        $hasMark = true;
        $answers = $this->student->student_lab_answers($course, $lab, $username, $visibility);

        if (sizeof($answers) > 0) {
            $answersList = "<ul class='answers-list'>
                    <li class='col-md-12 answer-row' id='answer-header'>
                        <div class='col-md-4 col-sm-4'>Question</div>
                        <div class='col-md-4 col-sm-4 hidden-xs '>Answer Submitted</div>
                        <div class='col-md-4 col-sm-4'>Mark</div>
                    </li>";

            foreach ($answers as $answer) {
                $answerText = $this->get_answer($answer);
                $answersList .= "<li class='col-md-12 answer-row'>
                        <div class='col-md-4 col-sm-4 col-xs-6'>$answer[0]</div>
                        <div class='col-md-4 col-sm-4 hidden-xs'>$answerText</div>
                        <div class='col-md-4 col-sm-4 col-xs-6'>$answer[5] / $answer[6]</div>
                       </li>";
            }
        } else {
            $answersList = "<div class='col-md-12 not-marked'>Student Has Not Been Marked</div>";
            $hasMark = false;
        }

        $answersList .= "</ul>";

        return [$answersList, $hasMark];
    }


    private function get_answer($answerArray)
    {
        switch ($answerArray[1]) {                               //Case statement checking what type each question is
            case "boolean":                                 //Inserts boolean type questions
                return ($answerArray[3] == "true") ? "Yes" : "No";
            case "scale":                                   //
                return $answerArray[2];
                break;
            case "text":
                return $answerArray[4];
                break;
            case "value":                                   //
                return $answerArray[2];

            default:
                return "Error occurred getting question type";
        }
    }
}