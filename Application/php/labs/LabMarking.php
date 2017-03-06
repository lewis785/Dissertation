<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 18:03
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../courses/CourseChecks.php");

class LabMarking extends CourseChecks
{

    public function getLabs($course)
    {
        $con = new ConnectDB();
        $labsArray = [];

        $get_labs = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_labs, "SELECT l.labName, l.canMark FROM labs AS l 
                                        JOIN courses AS c ON l.courseRef = c.courseID 
                                        WHERE c.courseName = ? ORDER BY l.labID");
        mysqli_stmt_bind_param($get_labs, 's', $course);
        mysqli_stmt_execute($get_labs);
        $result = mysqli_stmt_get_result($get_labs);

        while($lab = $result->fetch_row()) {
            array_push($labsArray, $lab);
        }

        mysqli_close($con->link);
        return $labsArray;
    }


    public function getMarkableLabs($course)
    {
        $con = new ConnectDB();

        $labs = [];
        if ($this->can_mark_course($course)) {
            $labs = $this->getLabs($course);
        }
        mysqli_close($con->link);
        return $labs;
    }


    public function markingLabsButtons($course)
    {
        $con = new ConnectDB();

        $_SESSION["MARKING_COURSE"] = $course;
        $result = $this->getLabs($course);
        $isLecturer = $this->has_access_level("lecturer");

        $buttons = "";

        if(sizeof($result)>0) {
            foreach ($result as $lab) {
                if ($isLecturer || $lab[1] === "true")
                    $buttons .= "<div class='col-md-6 col-md-offset-3'>
                          <button class='btn btn-success' id='btn-marking' onclick='display_students_for(\"" . $lab[0] . "\")'>" . $lab[0] . "</button>
                         </div>";
            }
        }
        else
            $buttons = "<div class='col-md-6 col-md-offset-3'>
                        No Labs Currently exist to be marked
                    </div>";

        mysqli_close($con->link);
        return  json_encode(array('successful'=>true, 'buttons'=>$buttons));
    }
}