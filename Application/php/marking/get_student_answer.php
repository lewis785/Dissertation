<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 25/02/2017
 * Time: 19:12
 */

require_once "classes/Marking.php";
if (session_status() == PHP_SESSION_NONE)
    session_start();

if($_SESSION["MARKING_COURSE"] && $_SESSION["MARKING_LAB"] && $_SESSION["MARKING_STUDENT"]) {
    if ($_SESSION["MARKING_COURSE"] !== "" && $_SESSION["MARKING_LAB"] !== "" && $_SESSION["MARKING_STUDENT"] !== "") {
        $marking = new Marking();
        echo($marking->getStudentAnswers($_SESSION["MARKING_COURSE"], $_SESSION["MARKING_LAB"], $_SESSION["MARKING_STUDENT"]));
    }
}