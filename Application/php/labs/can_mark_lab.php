<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 28/02/2017
 * Time: 17:39
 */

function is_lab_markable($link, $courseName, $labName)
{
    $getCanMark = 'SELECT canMark FROM labs AS l
                      JOIN courses AS c ON l.courseRef = c.courseID
                      WHERE c.courseName = ? AND l.labName = ?';            //Query gets if lab can be marked
    $getLabID = mysqli_stmt_init($link);                                    //Init Prepared Statement
    mysqli_stmt_prepare($getLabID, $getCanMark);
    mysqli_stmt_bind_param($getLabID, 'is',$courseName, $labName);          //Bind course and lab variables
    mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
    $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result
    return $result[0] === "true";                                           //Returns true if lab can be marked
}