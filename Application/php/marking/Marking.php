<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 00:43
 */
require_once (dirname(__FILE__)."/../core/ConnectDB.php");

class Marking
{
    public function already_marked($studentID, $labQID)
    {
        $con = new ConnectDB();

        $check_already_marked = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($check_already_marked, "SELECT count(*) FROM lab_answers WHERE socRef = ? AND labQuestionRef = ?");
        mysqli_stmt_bind_param($check_already_marked, 'ii', $studentID, $labQID);
        mysqli_stmt_execute($check_already_marked);
        $result = mysqli_stmt_get_result($check_already_marked)->fetch_row();
        return $result[0] === 1;
    }


}