<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 14/03/2017
 * Time: 23:18
 */

require_once "classes/LabDisplay.php";

if(isset($_POST["lab_id"]))
{
    $display = new LabDisplay();
    echo($display->labQuestions($_POST["lab_id"]));
}