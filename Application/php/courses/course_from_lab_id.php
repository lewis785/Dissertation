<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 28/02/2017
 * Time: 18:48
 */

function course_from_lab_id($link, $labID)
{
    $courseFromLabID = 'SELECT c.courseName FROM labs AS l
                        JOIN courses AS c ON l.courseRef = c.courseID
                        WHERE l.labID = ?';                //Query gets course name from lab id
    $getLabID = mysqli_stmt_init($link);                                    //Init Prepared Statement
    mysqli_stmt_prepare($getLabID, $courseFromLabID);
    mysqli_stmt_bind_param($getLabID, 'i',$labID);                  //Bind course and lab variables
    mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
    $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result
    return $result[0];
}