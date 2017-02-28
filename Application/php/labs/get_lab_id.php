<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 17:18
 * @param $link
 * @param $courseID
 * @param $lab
 * @return
 */

//Returns the ID number of the course passed into it
function get_lab_id($link, $course, $lab)
{
    $getLabIDQuery = 'SELECT labID FROM labs AS l
                      JOIN courses AS c ON l.courseRef = c.courseID
                      WHERE c.courseName = ? AND l.labName = ?';                //Query gets lab ID for course name and lab name
    $getLabID = mysqli_stmt_init($link);                                    //Init Prepared Statement
    mysqli_stmt_prepare($getLabID, $getLabIDQuery);
    mysqli_stmt_bind_param($getLabID, 'is',$course, $lab);                  //Bind course and lab variables
    mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
    $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result
    return $result[0];                                                      //Return first item in result array
}