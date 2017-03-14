<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 17:21
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
class LabChecks
{

    public function lab_already_exists($course, $lab)
    {
        $con = new ConnectDB();
        $checkLabExistsQuery = 'SELECT COUNT(*) FROM labs WHERE courseRef = ? AND labName = ?';
        $checkLabExists = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($checkLabExists, $checkLabExistsQuery);
        mysqli_stmt_bind_param($checkLabExists, 'is',$course, $lab);
        mysqli_stmt_execute($checkLabExists);                                //Executes the statement
        $result = mysqli_stmt_get_result($checkLabExists)->fetch_row();      //Retrieves the first rows results

        mysqli_close($con->link);
        return $result[0] == 1;                                              //Returns true is already exists;
    }


    public function is_lab_markable($courseName, $labName)
    {
        $con = new ConnectDB();

        $getCanMark = 'SELECT canMark FROM labs AS l
                      JOIN courses AS c ON l.courseRef = c.courseID
                      WHERE c.courseName = ? AND l.labName = ?';            //Query gets if lab can be marked
        $getLabID = mysqli_stmt_init($con->link);                                    //Init Prepared Statement
        mysqli_stmt_prepare($getLabID, $getCanMark);
        mysqli_stmt_bind_param($getLabID, 'is',$courseName, $labName);          //Bind course and lab variables
        mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
        $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result

        mysqli_close($con->link);
        return $result[0] === "true";                                           //Returns true if lab can be marked
    }


    //Returns the ID number of the course passed into it
    public function get_lab_id($course, $lab)
    {
        $con = new ConnectDB();

        $getLabIDQuery = 'SELECT labID FROM labs AS l
                      JOIN courses AS c ON l.courseRef = c.courseID
                      WHERE c.courseName = ? AND l.labName = ?';                //Query gets lab ID for course name and lab name
        $getLabID = mysqli_stmt_init($con->link);                                    //Init Prepared Statement
        mysqli_stmt_prepare($getLabID, $getLabIDQuery);
        mysqli_stmt_bind_param($getLabID, 'is',$course, $lab);                  //Bind course and lab variables
        mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
        $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result

        mysqli_close($con->link);
        return $result[0];                                                      //Return first item in result array
    }

}