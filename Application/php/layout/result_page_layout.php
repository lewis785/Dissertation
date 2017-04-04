<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 05/03/2017
 * Time: 18:18
 */

require_once(dirname(__FILE__)."/../core/classes/ConnectDB.php");
require_once(dirname(__FILE__)."/../core/classes/Security.php");


$sec = new Security();

if($sec->hasAccessLevel("lecturer"))
{
    echo "<script type='text/javascript' src='../../js/lecturer/lecturer_lab_results.js'></script>";
    include(dirname(__FILE__) . "/../lecturer/lecturer_lab_results.php");
}
else {
    echo "<script type='text/javascript' src='../../js/student/student_lab_results.js'></script>";
    include(dirname(__FILE__) . "/../students/display_lab_marks.php");
}