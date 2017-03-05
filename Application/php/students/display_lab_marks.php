<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:54
 */

include (dirname(__FILE__)."/../core/connection.php");

require_once "get_student_courses.php";
require_once (dirname(__FILE__)."/../labs/get_labs.php");
require_once (dirname(__FILE__)."/../labs/get_lab_id.php");
require_once (dirname(__FILE__)."/../labs/lab_total_mark.php");
require_once "student_lab_answers.php";
require_once "student_lab_mark.php";
require_once "get_matric_number.php";


$matricNum = get_student_matric($link, $_SESSION["username"]);
$courses = get_student_courses($matricNum);

$resultsTable = "";

$id = 0;
foreach($courses as $course)
{
    $labs = get_labs($course);
    $resultsTable.="<div class='col-md-12 results-course-row'><div class='col-md-6 col-md-offset-3'>$course</div></div><ul class='labs-list'>";


    $length = (sizeof($labs)-1);
    foreach($labs as $lab)
    {

        $resultsTable.= get_lab_summary($link,$matricNum, $course, $lab[0], $id);

        $labAnswers = student_lab_answers($course, $lab[0],$matricNum, "false");

        $rowNumOdd = true;

        $answersTable = "<ul class='answers-list'> 
                        <li class='col-md-12 answer-row' id='answer-header'>
                            <div class='col-md-4'>Question</div>
                            <div class='col-md-4'>Answer Submitted</div>
                            <div class='col-md-4'>Mark</div>
                        </li> ";
        foreach ($labAnswers as $answer)
        {
            switch ($answer[1]) {                               //Case statement checking what type each question is
                case "boolean":                                 //Inserts boolean type questions
                    $answerText = ($answer[3] == "true") ? "Yes" : "No";
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

            $answersTable.="<li class='col-md-12 answer-row $rowColor'>
                            <div id='question' class='col-md-4'> $answer[0]</div>
                            <div id='answer' class='col-md-4'>$answerText</div>
                            <div id='answer-mark' class='col-md-4'>$answer[5] / $answer[6]</div>
                            </li>";
        }


        $id++;
        $resultsTable.= $answersTable."</ul></li>";
    }
    $resultsTable.="</ul>";


}
echo $resultsTable;

mysqli_close($link);

function get_lab_summary($link,$matricNum, $course, $lab, $id)
{
    $curvedEdge = "";
    $labMark = lab_mark_for_student($link,$matricNum, $course, $lab);
    $maxMark = lab_total_mark($link, $course, $lab);

    if ($labMark != "") {
        $onclick = "onclick='change_div_size($id)'" ;
        $arrow = "glyphicon glyphicon-triangle-right";
        $mark = $labMark ." / ". $maxMark;
        if($maxMark != 0)
            $markPercentage = number_format((($labMark / $maxMark) * 100),2,".","") . "%";
    }
    else
    {
        $onclick = $arrow = "";
        $mark = $markPercentage = "Lab Not Marked Yet";
    }

    $output ="<li class='col-md-12 results-lab-row' id='result-row-$id' $onclick>
                            <div class='result-align-center result-summary col-md-12'>
                                <div id='result-row-arrow-$id' class='result-align-center col-md-1  glyphicon $arrow'></div>
                                <div class='col-md-3 col-md-offset-1'>Lab Name: $lab  </div>
                                <div class='col-md-3'>Mark: $mark</div>
                                <div class='col-md-3'>Percentage: $markPercentage</div>
                            </div>";
    return $output;
}
