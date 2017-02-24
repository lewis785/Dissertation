<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 17:18
 */

//Returns the ID number of the course passed into it
function get_lab_id($courseID, $lab)
{
    $link = $GLOBALS["link"];
    $getLabIDQuery = 'SELECT labID FROM labs WHERE courseRef = ? AND labName = ?';
    $getLabID = mysqli_stmt_init($link);
    mysqli_stmt_prepare($getLabID, $getLabIDQuery);
    mysqli_stmt_bind_param($getLabID, 'is',$courseID, $lab);
    mysqli_stmt_execute($getLabID);
    $result = mysqli_stmt_get_result($getLabID)->fetch_row();
    return $result[0];
}