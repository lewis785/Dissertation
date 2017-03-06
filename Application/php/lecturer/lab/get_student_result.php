<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 00:30
 */

require_once (dirname(__FILE__)."/../../students/student_lab_answers.php");

if(isset($_POST["course"]) && isset($_POST["lab"]) && isset($_POST["username"]) && isset($_POST["visible"]))
    get_student_result($_POST["course"], $_POST["lab"], $_POST["username"], $_POST["visible"]);



function get_student_result($course, $lab, $username, $visibility)
{
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
    }

    $answersList .="</ul>";

    echo json_encode(array("answers"=>$answersList));
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