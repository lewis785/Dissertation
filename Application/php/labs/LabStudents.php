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

            $buttons = "";
            while ($student = $result->fetch_row()) {

                $buttonType = $this->button_style($con->link, $student[2], $labID, $this->lab, $courseName);
                $buttons .= "<div class='col-md-6 col-md-offset-3 col-sm-12'>
                      <button class='" . $buttonType . "' id='btn-student' onclick='display_schema_for(\"" . $student[2] . "\")'>" . $student[0] . " " . $student[1] . "</button>
                     </div>";
            }
            $output = json_encode(array('successful' => true, 'buttons' => $buttons));
        } else
            $output = json_encode(array('successful' => false));

        mysqli_close($con->link);
        return $output;
    }


    private function button_style($link,$matric, $qID, $labName, $courseName)
    {

        if ($this->marking->already_marked($this->student->get_studentID($matric), $qID)) {
            if ($this->student->has_full_marks($link, $matric, $courseName, $labName))
                $style = "btn btn-success";
            elseif ($this->student->has_no_marks($link, $matric, $courseName, $labName))
                $style = "btn btn-danger";
            else
                $style = "btn btn-warning";

        } else
            $style = "btn btn-info";

        return $style;
    }


    public function get_students($course)
    {
        $output = [];
        if ($this->can_mark_course($course)) {
            $con = new ConnectDB();

            $get_students = mysqli_stmt_init($con->link);
            mysqli_stmt_prepare($get_students, "SELECT d.firstname, d.surname, d.studentID FROM students_on_courses AS soc 
                                        JOIN user_details AS d ON soc.student = d.detailsId 
                                        JOIN courses AS c ON soc.course = c.courseID 
                                        WHERE c.courseName = ? 
                                        ORDER BY d.surname, d.firstname");
            mysqli_stmt_bind_param($get_students, 's', $course);
            mysqli_stmt_execute($get_students);


            $output = mysqli_stmt_get_result($get_students);
            mysqli_close($con->link);
        }
        return $output;
    }

}


//$student = new LabStudents();
//echo($student->get_students_buttons("back"));