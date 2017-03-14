<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 00:15
 */

require_once "Lab.php";
require_once(dirname(__FILE__) . "/../students/Student.php");
require_once(dirname(__FILE__) . "/../marking/Marking.php");

class LabStudents extends Lab
{
    private $lab;
    private $type;
    private $student;
    private $marking;

    function __construct()
    {
        $this->lab = isset($_POST["lab"]) ? $_POST["lab"] : null;
        $this->type = isset($_POST["type"]) ? $_POST["type"] : null;

        $this->student = new Student();
        $this->marking = new Marking();
    }


    public function get_students_buttons()
    {
        $con = new ConnectDB();

        if ($this->can_mark_course($_SESSION["MARKING_COURSE"])) {
            if ($this->type === "next")
                $_SESSION["MARKING_LAB"] = $this->lab;
            else {
                $_SESSION["MARKING_STUDENT"] = "";
                $this->lab = $_SESSION["MARKING_LAB"];
            }
            $courseName = $_SESSION["MARKING_COURSE"];
            $labID = $this->get_questionID($this->get_lab_id($courseName, $this->lab), 1);

            $result = $this->get_students($courseName);

            $colour_key = "<div class='col-md-12 colour-keys'>
                           <div class='col-md-2 col-md-offset-3 col-sm-2 col-sm-offset-2 colour-key'><div class='colour-box not-marked-colour'/> <span>Not Marked</span></div>
                           <div class='col-md-2 col-sm-2 colour-key'><div class='colour-box marked-colour'/> <span>Already Marked</span></div>
                           <div class='col-md-2 col-sm-2 colour-key'><div class='colour-box full-marks-colour'/> <span>Full Marks</span></div></div>";

            $search_bar = "<div class='col-md-6 col-md-offset-3'>
                            <input type='text' id='student-search' class='col-md-12 form-control' placeholder='Student Search' onchange='filterDisplayedStudents(\"$this->lab\",this.value)'/>
                           </div>";

            $buttons = "";
            while ($student = $result->fetch_row()) {

                $buttonType = $this->button_style($con->link, $student[2], $labID, $this->lab, $courseName);
                $buttons .= "<div class='col-md-6 col-md-offset-3 col-sm-12 clickable-btn'>
                      <button class='" . $buttonType . " btn-text-wrap btn-student' onclick='display_schema_for(\"" . $student[2] . "\")'>" . $student[0] . " " . $student[1] . "</button>
                     </div>";
            }
                $output = json_encode(array('successful' => true, 'buttons' => $colour_key.$search_bar.$buttons));

        } else
            $output = json_encode(array('successful' => false));

        mysqli_close($con->link);
        return $output;
    }

    public function studentButtonsFilter($filter)
    {
        $con = new ConnectDB();
        $courseName = $_SESSION["MARKING_COURSE"];
        $labID =$this->get_questionID($this->get_lab_id($courseName, $this->lab), 1);
        $result = $this->get_students($courseName, $filter);
        $buttons = "";
        while ($student = $result->fetch_row()) {

            $buttonType = $this->button_style($con->link, $student[2], $labID, $this->lab, $courseName);
            $buttons .= "<div class='col-md-6 col-md-offset-3 col-sm-12 clickable-btn'>
                      <button class='" . $buttonType . " btn-text-wrap btn-student' onclick='display_schema_for(\"" . $student[2] . "\")'>" . $student[0] . " " . $student[1] . "</button>
                     </div>";
        }
        return json_encode(array('successful' => true, 'buttons' => $buttons));

    }

    private function button_style($link,$matric, $qID, $labName, $courseName)
    {
        if ($this->marking->already_marked($matric, $qID)) {
            if ($this->student->has_full_marks($link, $matric, $courseName, $labName))
                $style = "btn btn-success";
            else
                $style = "btn btn-warning";

        } else
            $style = "btn btn-info";

        return $style;
    }

}


//$student = new LabStudents();
//echo($student->get_students_buttons("back"));