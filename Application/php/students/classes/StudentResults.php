<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 13:50
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once "Student.php";

class StudentResults extends Student
{
    function __construct()
    {
        parent::__construct();
    }


    public function getStudentResults()
    {
        $con = new ConnectDB();

        $matricNum = $this->getStudentMatric($con->link, $_SESSION["username"]);
        $courses = $this->getStudentCourses($matricNum);

        $resultsTable = "";

        $id = 0;
        foreach ($courses as $course) {
            $labs = $this->lab->getLabs($course);
            $resultsTable .= "<div class='col-md-12 col-sm-12 col-xs-12 results-course-row'><div class='col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3'>$course</div></div><ul class='labs-list'>";


            if(sizeof($labs) > 0) {
                foreach ($labs as $lab) {

                    $resultsTable .= $this->getLabSummary($con->link, $matricNum, $course, $lab[0], $id);

                    $labAnswers = $this->studentLabAnswers($course, $lab[0], $matricNum, "false");

                    $rowNumOdd = true;

                    $answersTable = "<ul class='answers-list'> 
                        <li class='col-md-12 col-sm-12 col-xs-12  answer-row' id='answer-header'>
                            <div class='col-md-4 col-sm-4 col-xs-6'>Question</div>
                            <div class='col-md-4 col-sm-4 hidden-xs'>Answer Submitted</div>
                            <div class='col-md-4 col-sm-4 col-xs-6'>Mark</div>
                        </li> ";
                    foreach ($labAnswers as $answer) {
                        switch ($answer[1]) {                               //Case statement checking what type each question is
                            case "boolean":                                 //Inserts boolean type questions
                                $answerText = ($answer[3] == "true") ? "Yes" : "No";
                                break;
                            case "scale":                                   //
                                $answerText = $answer[2];
                                break;
                            case "text":
                                $answerText = $answer[4];
                                break;
                            case "value":                                   //
                                $answerText = $answer[2];
                                break;
                            default:
                                $answerText = "Error occurred getting question type";
                                break;
                        }

                        if ($rowNumOdd) {
                            $rowNumOdd = false;
                            $rowColor = "answer-color-odd";
                        } else {
                            $rowNumOdd = true;
                            $rowColor = "answer-color-even";
                        }

                        $answersTable .= "<li class='col-md-12 col-sm-12 col-xs-12 answer-row $rowColor'>
                            <div id='question' class='col-md-4 col-sm-4 col-xs-6'> $answer[0]</div>
                            <div id='answer' class='col-md-4 col-sm-4 hidden-xs'>$answerText</div>
                            <div id='answer-mark' class='col-md-4 col-sm-4 col-xs-6'>$answer[5] / $answer[6]</div>
                            </li>";
                    }
                    $id++;
                    $resultsTable .= $answersTable . "</ul></li>";
                }
            }
            else{
                $resultsTable .= "<li class='col-md-12 results-lab-row' id='result-row-$id' '>
                                    <div class='result-align-center result-summary col-md-12 col-sm-12 col-xs-12'>
                                        No Lab Exists For Course
                                    </div>
                                   </li>";
            }
            $resultsTable .= "</ul>";
        }
        mysqli_close($con->link);
        return $resultsTable;
    }

    private function getLabSummary($link, $matricNum, $course, $lab, $id)
    {
        $curvedEdge = "";
        $labMark = $this->labMarkForStudent($link, $matricNum, $course, $lab);
        $maxMark = $this->lab->labTotalMark($course, $lab);

        if ($labMark != "") {
            $onclick = "onclick='open_close_div($id)'";
            $arrow = "glyphicon glyphicon-triangle-right";
            $mark = $labMark . " / " . $maxMark;
            if ($maxMark != 0)
                $markPercentage = number_format((($labMark / $maxMark) * 100), 2, ".", "") . "%";
        } else {
            $onclick = $arrow = "";
            $mark = $markPercentage = "Lab Not Marked Yet";
        }

        $output = "<li class='col-md-12 col-sm-12 col-xs-12 results-lab-row' id='result-row-$id' $onclick>
                            <div class='result-align-center result-summary col-md-12 col-sm-12 col-xs-12'>
                                <div id='result-row-arrow-$id' class='result-align-center col-md-1 col-sm-1 col-xs-1  glyphicon $arrow'></div>
                                <div class='col-md-4 col-sm-4 col-xs-10'>Lab Name: $lab  </div>
                                <div class='col-md-3 col-sm-3 col-xs-12'>Mark: $mark</div>
                                <div class='col-md-4 col-sm-4 col-xs-12'>Percentage: $markPercentage</div>
                            </div>";
        return $output;
    }


}