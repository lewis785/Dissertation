<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 00:30
 */

require_once (dirname(__FILE__)."/../../students/student_lab_answers.php");
require_once (dirname(__FILE__)."/../../students/student_lab_mark.php");
require_once (dirname(__FILE__)."/../../labs/lab_total_mark.php");

if(isset($_POST["course"]) && isset($_POST["lab"]) && isset($_POST["username"]) && isset($_POST["visible"]))
    get_student_result($_POST["course"], $_POST["lab"], $_POST["username"], $_POST["visible"]);


function get_student_result($course, $lab, $username, $visibility)
{
    $stats = "";
    $answers = get_student_answers($course, $lab, $username, $visibility);

    if($answers[1])
        $stats = get_student_stats($course, $lab, $username);

    echo json_encode(array("stats"=>$stats, "answers"=>$answers[0]));
}

function get_student_stats($course, $lab, $username)
{
    $statsDiv = "";
    include (dirname(__FILE__)."/../../core/connection.php");
    $maxMark = lab_total_mark($link, $course, $lab);
    $studentMark = lab_mark_for_student($link,$username,$course,$lab);
    $percentage = number_format((($studentMark / $maxMark) * 100),2,".","") . "%";

    $statsDiv .="<div class='col-md-4'></div>";
    $statsDiv .="<div class='col-md-4'>Mark: $studentMark / $maxMark</div>";
    $statsDiv .="<div class='col-md-4'>Percentage: $percentage </div>";

    mysqli_close($link);
    return $statsDiv;
}

function get_student_answers($course, $lab, $username, $visibility)
{
    $hasMark = true;
    $answers = student_lab_answers($course, $lab, $username, $visibility);

    if(sizeof($answers) > 0) {
        $answersList = "<ul class='col-md-12' id='answers-list'>
                    <li class='col-md-12 answer-row' id='answer-header'>
                        <div class='col-md-4'>Question</div>
                        <div class='col-md-4'>Answer Submitted</div>
                        <div class='col-md-4'>Mark</div>
                    </li>";

        foreach ($answers as $answer) {
            $answerText = get_answer($answer);
            $answersList .= "<li class='col-md-12 answer-row'>
                        <div class='col-md-4'>$answer[0]</div>
                        <div class='col-md-4'>$answerText</div>
                        <div class='col-md-4'>$answer[5] / $answer[6]</div>
                       </li>";
        }
    }
    else{
        $answersList = "<div class='col-md-12 not-marked'>Student Has Not Been Marked</div>";
        $hasMark = false;
    }

    $answersList .="</ul>";

    return [$answersList, $hasMark];
}


function get_answer($answerArray)
{
    switch ($answerArray[1]) {                               //Case statement checking what type each question is
        case "boolean":                                 //Inserts boolean type questions
            return ($answerArray[3] == "true") ? "Yes" : "No";
        case "scale":                                   //
            return $answerArray[2];
            break;
        case "value":                                   //
            return $answerArray[2];

        default:
            return "Error occurred getting question type";
    }
}