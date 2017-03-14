<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 13:10
 */


require_once "classes/Marking.php";

if(isset($_POST["mark"]))
{
    $mark = new Marking();
    $mark->submitMark();
}
else{
    echo json_encode("ERROR");
}