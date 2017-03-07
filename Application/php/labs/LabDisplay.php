<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 17:17
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../courses/Courses.php");
require_once (dirname(__FILE__)."/../courses/CourseChecks.php");
require_once "LabChecks.php";
require_once "Lab.php";
require_once "LabMarking.php";


class LabDisplay extends LabChecks
{
    private $courses;
    private $course_checks;

    function  __construct()
    {
        $this->courses = new Courses();
        $this->course_checks = new CourseChecks();
    }


    public function displayLab($course, $lab)
    {
        $con = new ConnectDB();

        if ($this->course_checks->can_mark_course($course)) {
            $courseID = $this->courses->get_course_id($course);
            $labName = $lab;

            if ($this->lab_already_exists($courseID, $labName)) {
                $retrieveQuestionsQuery = 'SELECT lq.questionNumber, qt.typeName, lq.question, lq.minMark, lq.maxMark FROM lab_questions AS lq
                              JOIN question_types AS qt ON lq.questionType = qt.questionTypeID
                              JOIN labs ON lq.labRef = labs.labID
                              WHERE labs.labName = ? AND courseRef = ? 
                              ORDER BY lq.questionNumber';
                $retrieveQuestions = mysqli_stmt_init($con->link);
                mysqli_stmt_prepare($retrieveQuestions, $retrieveQuestionsQuery);
                mysqli_stmt_bind_param($retrieveQuestions, 'si', $labName, $courseID);
                mysqli_stmt_execute($retrieveQuestions);
                $result = mysqli_stmt_get_result($retrieveQuestions);

                $outputHtml = "<form class=\"col-lg-12\" id=\"form-area\" accept-charset=\"UTF-8\" role=\"form\"  name=\"marking-form\" method=\"post\" action=\"../../php/marking/submit_mark.php\">";
                while ($question = $result->fetch_row()) {
                    $outputHtml = $outputHtml . $this->display_question($question);
                }
                mysqli_close($con->link);
                return json_encode(array('html' => $outputHtml . "</form>"));
            }
        }
        mysqli_close($con->link);
        return json_encode(array('html' => "Failed to display lab" . "</form>"));
    }


    //Function returns a JSON object containing the html for the labs table
    public function labManagementTable()
    {
        $con = new ConnectDB();
        $stats = new Lab();
        $labList = new LabMarking();

        if ($this->course_checks->has_access_level("lecturer")) {                                              //Checks user has access level of lecturer

            $output = "<table class=\"table table-responsive labs-table\"'><tbody>";                //Creates output variable containing start code for table
            $courses = $this->courses->get_courses();                                                           //Gets all the courses the lecturer has access to

            foreach($courses as $course) {                                           //For loop through each course
                $labs = $labList->getMarkableLabs($course);                                                   //Stores all the labs relating to the course
                $output.= "<tr><td class='btn-info course-row'\" colspan=3>".$course."</td></tr>";   //Insert Row to filling it with the course title
                if(sizeof($labs) > 0) {                                                         //Checks that there is at least one lab
                    foreach ($labs as $lab) {                                                   //For loop through each lab the course has
                        $id = $this->get_lab_id($course[0], $lab[0]);                              //Gets the labID for the lab
                        $totalMark = $stats->lab_total_mark($course,$lab[0]);

                        if ($this->is_lab_markable($course,$lab[0]))
                            $buttonChecked = "checked='checked' onclick='lab_markable(".$id.",\"false\")'";
                        else
                            $buttonChecked = "onclick='lab_markable(".$id.",\"true\")'";


                        $output .= "<tr id='lab-".$id."'><td class='lab-row col-md-4'>" . $lab[0] . "</td>";
                        $output .= "<td class='col-md-2'>Max Mark: ".$totalMark."</td>";
                        $output .= "<td class='col-md-2'><input id='check-".$id."' type='checkbox'".$buttonChecked." value=''> Markable</td>";
                        $output .= "<td class='col-md-2'><button class='btn btn-warning col-md-6 col-md-offset-3'onclick='*'>Edit</button></td>";
                        $output .= "<td class='col-md-2'><button class='btn btn-danger col-md-6 col-md-offset-3' onclick='delete_popup(".$id.")'>Delete</button>";
                        $output .= "</td></tr>";
                    }
                }
                else                                                                            //If course has no labs
                    $output .= "<tr><td colspan=3><div class='col-md-offset-5 col-md-3'>No Labs Exist</div></td></tr>";     //Adds row stating course has no labs
            }
            $output.="</tbody></table>";                                                        //Adds closing tags for table
            mysqli_close($con->link);                                                                    //Closes the DB connection
            return json_encode(array("success"=> true, "table"=> $output));                       //Echos JSON Object containing table
        }
        else {                                                                                  //If user does not have lecturer access
            mysqli_close($con->link);                                                                    //Closes the DB connection
            return json_encode(array("success"=>false, "error-message"=>"You do not have access to run this function"));  //Echos JSON Object stating load failed and an error message
        }
    }





    private function display_question($question)
    {
        $html = '<div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2 tile"  id="question-'. $question[0] .'">
                <div class="col-md-5 col-md-offset-1"><label for="sel1">Question Number: <div id="question-number">'.$question[0].'</div></label></div>
                <div class="form-group row">
                    <label for="question-label-input" class="col-md-12 col-md-offset-1 col-form-label">'.$question[2].'</label>
                </div>';

        switch ($question[1]) {                                       //Case statement checking what type each question is
            case "boolean":                                 //Inserts boolean type questions
                $html = $html . $this->question_boolean($question[0]);
                break;
            case "scale":                                   //Inserts scale type questions
                $html = $html . $this->question_scale($question[3], $question[4]);
                break;
            case "value":                                   //Inserts value type questions

                break;
            default:
//            echo "default";                             //Default if type doesn't exist
        }

        return $html."</div>";
    }


//Returns the layout for a scale question
    private function question_scale($start, $end)
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
    private function question_boolean($id)
    {
        $id = "boolean-button-".$id;
        return '<div class="col-md-4 col-md-offset-4">
                <input type="hidden" class="question-type" name="type[]" value="boolean">
                <input id="'.$id.'-hidden" class="mark-input" type = "hidden" name="mark[]" value = "false"/>
                <input id="'.$id.'" class="btn btn-danger col-md-12 lab-input" style="width:100%;"type="button" value="no" onclick="swap_value(\''. $id .'\')">
            </div>';
    }
}


//$display = new LabDisplay();
//echo $display->displayLab("Software Development 1", "df");

//if(isset($_POST['lab']) && isset($_POST['course']))
//{
//    $course = $_POST["course"];
//    $lab = $_POST['lab'];
//}
//else
//{
//    if(isset($_POST["student"]))
//        $_SESSION["MARKING_STUDENT"] = $_POST["student"];
//    $course = $_SESSION["MARKING_COURSE"];
//    $lab = $_SESSION["MARKING_LAB"];
//}
