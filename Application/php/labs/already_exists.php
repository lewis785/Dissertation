<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 19/02/2017
 * Time: 00:57
 */

//Returns true if name of lab is already used by the course
function lab_already_exists($course, $lab)
{
    $link = $GLOBALS['link'];
    $checkLabExistsQuery = 'SELECT COUNT(*) FROM labs WHERE courseRef = ? AND labName = ?';
    $checkLabExists = mysqli_stmt_init($link);
    mysqli_stmt_prepare($checkLabExists, $checkLabExistsQuery);
    mysqli_stmt_bind_param($checkLabExists, 'is',$course, $lab);
    mysqli_stmt_execute($checkLabExists);                                //Executes the statement
    $result = mysqli_stmt_get_result($checkLabExists)->fetch_row();      //Retrieves the first rows results
    return $result[0] == 1;                                              //Returns true is already exists;
}