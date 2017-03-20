<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 18:23
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once(dirname(__FILE__) . "/../../courses/classes/CourseChecks.php");
require_once(dirname(__FILE__) . "/../../core/classes/IO.php");
require_once "LabDisplay.php";
require_once "Lab.php";

class LabManager extends CourseChecks
{
    private $labID;
    private $state;
    private $LabDisplay;

    function __construct()
    {
        $this->labID = isset($_POST["labID"]) ? $_POST["labID"] : null;
        $this->state = isset($_POST["newState"]) ? $_POST["newState"] : null;
        $this->LabDisplay = new LabDisplay();
    }


    //Function returns a JSON object containing the html for the labs table
    public function labManagementTable()
    {
        $con = new ConnectDB();
        $stats = new Lab();
        $labList = new LabMarking();

        if ($this->has_access_level("lecturer")) {                                              //Checks user has access level of lecturer

            $output = "<table class='table table-responsive labs-table'><tbody>";                //Creates output variable containing start code for table
            $courses = $this->get_courses();                                                           //Gets all the courses the lecturer has access to

            foreach ($courses as $course) {                                           //For loop through each course
                $labs = $labList->getMarkableLabs($course);                                                   //Stores all the labs relating to the course
                $output .= "<tr><td class='btn-info course-row'' colspan=6>" . $course . "</td></tr>";   //Insert Row to filling it with the course title
                if (sizeof($labs) > 0) {                                                         //Checks that there is at least one lab
                    foreach ($labs as $lab) {                                                   //For loop through each lab the course has
                        $id = $this->LabDisplay->get_lab_id($course, $lab[0]);                              //Gets the labID for the lab
                        $totalMark = $stats->lab_total_mark($course, $lab[0]);


                        if ($this->LabDisplay->is_lab_markable($course, $lab[0]))
                            $buttonChecked = "checked='checked' onclick='lab_markable(" . $id . ",\"false\")'";
                        else
                            $buttonChecked = "onclick='lab_markable(" . $id . ",\"true\")'";


                        $output .= "<tr id='lab-" . $id . "'><td class='lab-row col-md-2'>" . $lab[0] . "</td>";
                        $output .= "<td class='col-md-2'>Max Mark: " . $totalMark . "</td>";
                        $output .= "<td class='col-md-2'><input id='check-" . $id . "' type='checkbox'" . $buttonChecked . " value=''> Markable</td>";
                        $output .= "<td class='col-md-2'><button class='btn btn-success col-md-8 col-md-offset-2'onclick='exportResults($id)'>Export Results</button></td>";
                        $output .= "<td class='col-md-2'><button class='btn btn-warning col-md-8 col-md-offset-2'onclick='editLab(\"$course: $lab[0]\",$id)'>Edit</button></td>";
                        $output .= "<td class='col-md-2'><button class='btn btn-danger col-md-8 col-md-offset-2' onclick='delete_popup($id )'>Delete</button>";
                        $output .= "</td></tr>";
                    }
                } else                                                                            //If course has no labs
                    $output .= "<tr><td colspan=3><div class='col-md-offset-5 col-md-3'>No Labs Exist</div></td></tr>";     //Adds row stating course has no labs
            }
            $output .= "</tbody></table>";                                                        //Adds closing tags for table
            mysqli_close($con->link);                                                                    //Closes the DB connection
            return json_encode(array("success" => true, "table" => $output));                       //Echos JSON Object containing table
        } else {                                                                                  //If user does not have lecturer access
            mysqli_close($con->link);                                                                    //Closes the DB connection
            return json_encode(array("success" => false, "error-message" => "You do not have access to run this function"));  //Echos JSON Object stating load failed and an error message
        }
    }


    public function changeMarkable()
    {
        $course = $this->courseFromLabID($this->labID);
        $successful = false;
        $con = new ConnectDB();

        if ($this->is_lecturer_of_course($course)) {

            $changeMarkState = mysqli_stmt_init($con->link);
            mysqli_stmt_prepare($changeMarkState, "UPDATE labs SET canMark = ? WHERE labID = ?");
            mysqli_stmt_bind_param($changeMarkState, "si", $this->state, $this->labID);
            $successful = mysqli_stmt_execute($changeMarkState);
        }
        mysqli_close($con->link);
        return json_encode(array("success"=>$successful));
    }

    public function exportLabResults()
    {
        if($this->has_access_level("lecturer")){

        $io = new IO();
        $Lab = new Lab();
        $con = new ConnectDB();

        $questionText = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($questionText, "SELECT lq.question FROM labs AS l JOIN lab_questions AS lq ON l.labID = lq.labRef
                                            WHERE l.labID = ?");
        mysqli_stmt_bind_param($questionText, "s", $this->labID);
        mysqli_stmt_execute($questionText);
        $results = mysqli_stmt_get_result($questionText);

        $titles_array = ['Marticulation Number'];
        while ($question = $results->fetch_row())
            array_push($titles_array, $question[0]);
        $num_of_questions = sizeof($titles_array) - 1;
        array_push($titles_array, "Total Mark");


        $students = $Lab->studentsFromLabID($this->labID);

        $studentMarks = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($studentMarks, "SELECT group_concat(IFNULL( la.mark, 0 )) FROM lab_answers AS la 
                                            JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID 
                                            JOIN students_on_courses AS soc ON la.socRef = soc.socID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId 
                                            WHERE ud.studentID = ? AND lq.labRef = ?");

        $output_array = [];
        foreach ($students as $student) {
            mysqli_stmt_bind_param($studentMarks, "si", $student, $this->labID);
            mysqli_stmt_execute($studentMarks);
            $results = mysqli_stmt_get_result($studentMarks)->fetch_row();
            if (sizeof($results[0]) !== 0)
                $marks = (explode(",", $results[0]));
            else
                $marks = array_fill(0, $num_of_questions, 0);
            array_push($marks, array_sum($marks));
            array_push($output_array, array_merge([$student], $marks));
        }

        $io->export($Lab->labFromID($this->labID) . " Results", $titles_array, $output_array);

        return json_encode(array("success" => true));
    }


    }


    public function editLab()
    {

    }

    public function deleteLab()
    {
        if ($this->has_access_level("lecturer")) {
            if ($this->labID !== null) {
                $con = new ConnectDB();
                $course = $this->courseFromLabID($this->labID);

                if ($this->is_lecturer_of_course($course))                                            //Checks if user has access to edit the course
                {
                    $delete_lab = mysqli_stmt_init($con->link);                                              //Init Prepared Statment
                    mysqli_stmt_prepare($delete_lab, "DELETE FROM labs WHERE labID = ?");               //Query deletes labs that match the labID
                    mysqli_stmt_bind_param($delete_lab, "i", $this->labID);                                  //Bind labID to query
                    mysqli_stmt_execute($delete_lab);                                                   //Execute prepared statement

                    $deletion = true;                                                                   //Return success as true
                } else                                                                                    //If user does not
                    $deletion = false;                                                                  //Return success as false


                mysqli_close($con->link);                                                                    //Closes DB connection
                return json_encode(array("success" => $deletion));
            } else
                return json_encode(array("success" => false));
        }
    }

}