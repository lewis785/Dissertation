<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 18:03
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once "Lab.php";

class LabMarking extends Lab
{

    private $course;
    private $type;

    function __construct()
    {
        $this->course = isset($_POST["course"]) ? $_POST["course"] : null;
        $this->type = isset($_POST["type"]) ? $_POST["type"] : null;
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


    public function markingLabsButtons()
    {
        $con = new ConnectDB();

        if($this->type === "next")
            $_SESSION["MARKING_COURSE"] = $this->course;
        else
            $_SESSION["MARKING_LAB"] = "";

        $result = $this->getLabs($_SESSION["MARKING_COURSE"]);
        $isLecturer = $this->has_access_level("lecturer");

        $buttons = "";

        $lab_exists = false;
        foreach ($result as $lab) {
            if ($isLecturer || $lab[1] === "true") {
                $buttons .= "<div class='col-md-6 col-md-offset-3 col-xs-offset-0'>
                      <button class='btn btn-success btn-text-wrap' id='btn-marking' onclick='display_students_for(\"" . $lab[0] . "\")'>" . $lab[0] . "</button>
                     </div>";
            $lab_exists = true;
            }
        }

        if(!$lab_exists)
            $buttons = "<div class='col-md-6 col-md-offset-3'>

                            <button class='btn btn-warning btn-text-wrap' id='btn-marking'>No labs currently exist to be marked</button>
                        </div>";


        mysqli_close($con->link);
        return  json_encode(array('successful'=>true, 'buttons'=>$buttons));
    }
}