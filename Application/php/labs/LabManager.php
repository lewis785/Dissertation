<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 18:23
 */

require_once(dirname(__FILE__) . "/../core/ConnectDB.php");
require_once(dirname(__FILE__) . "/../courses/CourseChecks.php");


class LabManager extends CourseChecks
{

    private $labID;
    private $state;

    function __construct()
    {
        $this->labID = isset($_POST["labID"]) ? $_POST["labID"] : null;
        $this->state = isset($_POST["newState"]) ? $_POST["newState"] : null;


    }


    public function changeMarkable($labID, $state)
    {
        $course = $this->course_from_lab_id($labID);
        $successful = false;
        $con = new ConnectDB();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            echo "session started";
        }
        echo $_SESSION["username"];
        if ($this->is_lecturer_of_course($course)) {


            $changeMarkState = mysqli_stmt_init($con->link);
            mysqli_stmt_prepare($changeMarkState, "UPDATE labs SET canMark = ? WHERE labID = ?");
            mysqli_stmt_bind_param($changeMarkState, "si", $state, $labID);
            $successful = mysqli_stmt_execute($changeMarkState);
            mysqli_close($con->link);
        }

        return json_encode(array("success"=>$successful));
    }


    public function deleteLab()
    {
        if($this->labID !== null)
        {
            $con = new ConnectDB();

            $labID_to_course_name = mysqli_stmt_init($con->link);                                        //Init Prepared Statement
            mysqli_stmt_prepare($labID_to_course_name, "SELECT c.courseName FROM courses AS c
                                                JOIN labs AS l ON c.courseID = l.courseRef
                                                WHERE l.labID = ?");                        //Query retrieves name of the course the lab belongs too
            mysqli_stmt_bind_param($labID_to_course_name, "i", $labID );                            //Binds labID to query
            mysqli_stmt_execute($labID_to_course_name);                                             //Executes Prepared Statement
            $course = mysqli_stmt_get_result($labID_to_course_name)->fetch_row();                   //Retrieves result of query

            if($this->is_lecturer_of_course($course[0]))                                            //Checks if user has access to edit the course
            {
                $delete_lab = mysqli_stmt_init($con->link);                                              //Init Prepared Statment
                mysqli_stmt_prepare($delete_lab, "DELETE FROM labs WHERE labID = ?");               //Query deletes labs that match the labID
                mysqli_stmt_bind_param($delete_lab, "i", $this->labID );                                  //Bind labID to query
                mysqli_stmt_execute($delete_lab);                                                   //Execute prepared statement

                $deletion = true;                                                                   //Return success as true
            }
            else                                                                                    //If user does not
            {
                $deletion = false;                                                                  //Return success as false
            }

            mysqli_close($con->link);                                                                    //Closes DB connection
            return json_encode(array("success"=>$deletion));
        }
    }
}

$manage =  new LabManager();
echo($manage->changeMarkable(14, "false"));

//if(isset($_POST["labID"]) && isset($_POST["newState"]))
