<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 17:17
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once(dirname(__FILE__) . "/../../courses/classes/Courses.php");
require_once(dirname(__FILE__) . "/../../courses/classes/CourseChecks.php");
require_once "LabChecks.php";
require_once "Lab.php";
require_once "LabMarking.php";


class LabDisplay extends LabChecks
{
    private $courses;
    private $course_checks;

    function __construct()
    {
        $this->courses = new Courses();
        $this->course_checks = new CourseChecks();
    }


    public function displayLab($course, $lab)
    {
        $con = new ConnectDB();

        if ($this->course_checks->can_mark_course($course)) {
            $courseID = $this->courses->getCourseId($course);
            $labName = $lab;

            if ($this->lab_already_exists($courseID, $labName)) {

                $questions =  $this->getLabQuestions($con->link, $labName, $course);

                $outputHtml = "<form class='col-lg-12' id='form-area' accept-charset='UTF-8' role='form'  name='marking-form' method='post' action='../../marking/submit_mark.php'>";
                foreach($questions as $question) {
                    $outputHtml = $outputHtml . $this->display_question($question);
                }
                mysqli_close($con->link);
                return json_encode(array('html' => $outputHtml . "</form>"));
            }
        }
        mysqli_close($con->link);
        return json_encode(array('html' => "Failed to display lab" . "</form>"));
    }

    public function labQuestions($lab_id)
    {

        $con = new ConnectDB();
        $Lab = new Lab();
        $questions = $this->getLabQuestions($con->link, $Lab->labFromID($lab_id), $this->courses->courseFromLabID($lab_id));
        mysqli_close($con->link);

        return json_encode(array("questions"=>$questions));
    }

    private function getLabQuestions($link, $lab_name, $course_name)
    {

        $retrieveQuestionsQuery = 'SELECT lq.questionNumber, qt.typeName, lq.question, lq.minMark, lq.maxMark, lq.private FROM lab_questions AS lq
                              JOIN question_types AS qt ON lq.questionType = qt.questionTypeID
                              JOIN labs ON lq.labRef = labs.labID
                              JOIN courses AS c ON labs.courseRef = c.courseID
                              WHERE labs.labName = ? AND c.courseName = ? 
                              ORDER BY lq.questionNumber';
        $retrieveQuestions = mysqli_stmt_init($link);
        mysqli_stmt_prepare($retrieveQuestions, $retrieveQuestionsQuery);
        mysqli_stmt_bind_param($retrieveQuestions, 'si', $lab_name, $course_name);
        mysqli_stmt_execute($retrieveQuestions);
        $result = mysqli_stmt_get_result($retrieveQuestions);
        $output_array = [];

        while($question = $result->fetch_row()) {
            array_push($output_array, $question);
        }

        return $output_array;
    }


    private function display_question($question)
    {
        $html = '<div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2 tile"  id="question-' . $question[0] . '">
                <div class="col-md-5 col-md-offset-1"><label for="sel1">Question Number: <div id="question-number">' . $question[0] . '</div></label></div>
                <div class="form-group row">
                    <label for="question-label-input" class="col-md-12 col-md-offset-1 col-form-label">' . $question[2] . '</label>
                </div>';

        switch ($question[1]) {                                       //Case statement checking what type each question is
            case "boolean":                                 //Inserts boolean type questions
                $html = $html . $this->question_boolean($question[0]);
                break;
            case "scale":                                   //Inserts scale type questions
                $html = $html . $this->question_scale($question[3], $question[4]);
                break;
            case "text":
                $html = $html . $this->textQuestion($question[4]);
                break;
            case "value":                                   //Inserts value type questions
                break;
            default:
//            echo "default";                             //Default if type doesn't exist
        }

        return $html . "</div>";
    }


//Returns the layout for a scale question
    private function question_scale($start, $end)
    {
        $scale = '<div class="form-group col-md-4 col-md-offset-4">
                <input type="hidden" class="question-type" name="type[]" value="scale">
                <label for="sel1">Select Mark (select one):</label>
                <select class="form-control mark-input lab-input" name="mark[]" id="scale-input">
                ';
        for ($i = $start; $i <= $end; $i++) {
            $scale .= '<option value="' . $i . '">' . $i . '</option>';
        }
        return $scale . '</select></div>';
    }


//Returns the layout for a boolean question
    private function question_boolean($id)
    {
        $id = "boolean-button-" . $id;
        return '<div class="col-md-4 col-md-offset-4">
                <input type="hidden" class="question-type" name="type[]" value="boolean">
                <input id="' . $id . '-hidden" class="mark-input" type = "hidden" name="mark[]" value = "false"/>
                <input id="' . $id . '" class="btn btn-danger col-md-12 lab-input" style="width:100%;"type="button" value="no" onclick="swap_value(\'' . $id . '\')">
            </div>';
    }

    //Returns the layout for a text question
    private function textQuestion($maxMark)
    {
        $output = "<div class='col-md-6 col-md-offset-3'>
                    <input type='hidden' class='question-type' name='type[]' value='text'>
                     <textarea class='col-md-12 lab-input' name='text[]'></textarea>";
        if ($maxMark > 0) {
            $output .="<label for='sel1'>Select Mark (select one):</label>
                        <select class='form-control mark-input text-value-selector' name='mark[]' id='scale-input'>";

            for ($i = 0; $i <= $maxMark; $i++) {
                $output .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $output.="</select>";
        }
        else
            $output .="<input type='hidden' name='mark[]' value='0'>";
        return $output."</div>";
    }
}