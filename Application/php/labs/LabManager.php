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

    public function printPost()
    {
        echo $this->labID;
        echo $this->state;
    }

    public function changeMarkable()
    {
        $course = $this->course_from_lab_id($this->labID);
        $successful = false;
        $con = new ConnectDB();


        if ($this->is_lecturer_of_course($course)) {

            $changeMarkState = mysqli_stmt_init($con->link);
            mysqli_stmt_prepare($changeMarkState, "UPDATE labs SET canMark = ? WHERE labID = ?");
            mysqli_stmt_bind_param($changeMarkState, "si", $this->state, $this->labID);
            $successful = mysqli_stmt_execute($changeMarkState);
            mysqli_close($con->link);
        }

        return json_encode(array("success"=>$successful));
    }


    public function deleteLab()
    {
//        echo "hello: ".$this->labID;
        if($this->labID !== null)
        {

            $con = new ConnectDB();

            $course = $this->course_from_lab_id($this->labID);

            if($this->is_lecturer_of_course($course))                                            //Checks if user has access to edit the course
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
        else
            return json_encode(array("success"=>false));
    }

}

//$manage = new LabManager();
//echo ($manage->deleteLab());


//if(isset($_POST["labID"]) && isset($_POST["newState"]))
