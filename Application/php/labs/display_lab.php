<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 19/02/2017
 * Time: 00:33
 */
include(dirname(__FILE__)."/../core/connection.php");
include 'already_exists.php';
include(dirname(__FILE__)."/../courses/get_course_id.php");
include(dirname(__FILE__)."/../courses/course_checks.php");

if(isset($_POST['lab']) && isset($_POST['course']))
{
    $course = $_POST["course"];
    $lab = $_POST['lab'];
}
else
{
    if(isset($_POST["student"]))
        $_SESSION["MARKING_STUDENT"] = $_POST["student"];
    $course = $_SESSION["MARKING_COURSE"];
    $lab = $_SESSION["MARKING_LAB"];
}


if(can_mark_course($link,$course))
{
    $courseID = get_course_id($course);
    $labName = $lab;

    if(lab_already_exists($courseID,$labName))
    {
        $link = $GLOBALS["link"];
        $retrieveQuestionsQuery = 'select lq.questionNumber, qt.typeName, lq.question, lq.minMark, lq.maxMark from lab_questions as lq
                              join question_types as qt on lq.questionType = qt.questionTypeID
                              join labs on lq.labRef = labs.labID
                              where labs.labName = ? and courseRef = ? 
                              order by lq.questionNumber';
        $retrieveQuestions = mysqli_stmt_init($link);
        mysqli_stmt_prepare($retrieveQuestions, $retrieveQuestionsQuery);
        mysqli_stmt_bind_param($retrieveQuestions, 'si',$labName, $courseID);
        mysqli_stmt_execute($retrieveQuestions);
        $result = mysqli_stmt_get_result($retrieveQuestions);

        $outputHtml = "<form class=\"col-lg-12\" id=\"form-area\" accept-charset=\"UTF-8\" role=\"form\"  name=\"marking-form\" method=\"post\" action=\"../../php/marking/submit_mark.php\">";
        while ($question = $result->fetch_row()) {
            $outputHtml = $outputHtml . display_question($question);
        }
        echo json_encode(array('html'=>$outputHtml."</form>"));
    }
}
mysqli_close($link);


function display_question($question)
{
    $html = '<div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2 tile"  id="question-'. $question[0] .'">
                <div class="col-md-5 col-md-offset-1"><label for="sel1">Question Number: <div id="question-number">'.$question[0].'</div></label></div>
                <div class="form-group row">
                    <label for="question-label-input" class="col-md-12 col-md-offset-1 col-form-label">'.$question[2].'</label>
                </div>';

    switch ($question[1]) {                                       //Case statement checking what type each question is
        case "boolean":                                 //Inserts boolean type questions
            $html = $html . question_boolean($question[0]);
            break;
        case "scale":                                   //Inserts scale type questions
            $html = $html . question_scale($question[3], $question[4]);
            break;
        case "value":                                   //Inserts value type questions

            break;
        default:
//            echo "default";                             //Default if type doesn't exist
    }

    return $html."</div>";
}


//Returns the layout for a scale question
function question_scale($start, $end)
{
    $scale = '<div class="form-group col-md-4 col-md-offset-4">
                <input type="hidden" class="question-type" name="type[]" value="scale">
                <label for="sel1">Select Mark (select one):</label>
                <select class="form-control mark-input lab-input" name="mark[]" id="scale-input">
                <option selected value="no-selection">Select Value</option>';
    for($i = $start-1; $i<=$end; $i++)
    {
        $scale .= '<option value="'.$i.'">'.$i.'</option>';
    }
    return  $scale.'</select></div>';
}


//Returns the layout for a boolean question
function question_boolean($id)
{
    $id = "boolean-button-".$id;
    return '<div class="col-md-4 col-md-offset-4">
                <input type="hidden" class="question-type" name="type[]" value="boolean">
                <input id="'.$id.'-hidden" class="mark-input" type = "hidden" name="mark[]" value = "false"/>
                <input id="'.$id.'" class="btn btn-danger col-md-12 lab-input" style="width:100%;"type="button" value="no" onclick="swap_value(\''. $id .'\')">
            </div>';
}





