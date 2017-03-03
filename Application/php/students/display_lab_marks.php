<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:54
 */

require_once "get_student_courses.php";
require_once (dirname(__FILE__)."/../labs/get_labs.php");
require_once (dirname(__FILE__)."/../labs/get_lab_id.php");
require_once "student_lab_answers.php";


$courses = get_student_courses();
$resultsTable = "";

$id = 0;
foreach($courses as $course)
{
    $labs = get_labs($course);
    $resultsTable.="<div class='col-md-12 results-course-row'><div class='col-md-6 col-md-offset-3'>$course</div></div>";


    $length = (sizeof($labs)-1);
    foreach($labs as $index=>$lab)
    {
        $curvedEdge = "";
//        $labID = get_lab_id($link,$course, $lab);
        $labAnswers = student_lab_answers($course, $lab[0]);

        if($index == $length )
            $curvedEdge = "last-lab-row";

        $resultsTable.="<div class='col-md-12 results-lab-row $curvedEdge' id='result-row-$id' onclick='change_div_size($id)'>
                            <div class='result-align-center result-summary col-md-12'>
                                <div id='result-row-arrow-$id' class='result-align-center col-md-1 glyphicon glyphicon-triangle-right'></div>
                                <div class='col-md-3 col-md-offset-3'>$lab[0]</div>
                            </div>
                            <ul class='labs-list'>";


        $rowNumOdd = true;
        foreach ($labAnswers as $answer)
        {
            switch ($answer[1]) {                               //Case statement checking what type each question is
                case "boolean":                                 //Inserts boolean type questions
                    $answerText = $answer[3];
                    break;
                case "scale":                                   //
                    $answerText = $answer[2];
                    break;
                case "value":                                   //
                    $answerText = $answer[2];
                    break;
                default:
                    $answerText = "Error occurred getting question type";
                    break;
            }

            if($rowNumOdd)
            {
                $rowNumOdd = false;
                $rowColor = "answer-color-odd";
            }
            else{
                $rowNumOdd = true;
                $rowColor = "answer-color-even";
            }

            $resultsTable.="<li class='col-md-12 answer-row $rowColor'>
                            <div id='question' class='col-md-4'>Question: <br> $answer[0]</div>
                            <div id='answer' class='col-md-4'>Answer: <br> $answerText</div>
                            <div id='answer-mark' class='col-md-4'>Mark:<br> $answer[5] / $answer[6]</div>
                            </li>";
        }


        $id++;
        $resultsTable.= "</div>";
    }


}
echo $resultsTable;