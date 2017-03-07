<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 17:54
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../courses/CourseChecks.php");



class Lab extends CourseChecks
{
    public function lab_total_mark($courseName, $labName)
    {
        $con = new ConnectDB();

        $labTotalMark = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($labTotalMark, "SELECT SUM(maxMark) FROM lab_questions as lq 
                                        JOIN labs AS l ON lq.labRef = l.labID
                                        JOIN courses AS c ON l.courseRef = c.courseID
                                        WHERE c.courseName = ? AND l.labName = ? ");
        mysqli_stmt_bind_param($labTotalMark, "ss", $courseName, $labName);

        if(mysqli_stmt_execute($labTotalMark))
        {
            $result = mysqli_stmt_get_result($labTotalMark)->fetch_row();
            mysqli_close($con->link);
            return ($result[0]);
        }
        mysqli_close($con->link);
        return -1;
    }

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


    public function get_questionID($labID, $questionNum)
    {
        $con = new ConnectDB();

        $get_questionID = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_questionID, "SELECT questionID FROM lab_questions WHERE labRef = ? AND questionNumber = ?");
        mysqli_stmt_bind_param($get_questionID, 'ii', $labID, $questionNum);
        mysqli_stmt_execute($get_questionID);
        $result = mysqli_stmt_get_result($get_questionID)->fetch_row();

        mysqli_close($con->link);
        return $result[0];
    }

}