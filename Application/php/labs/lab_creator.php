<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 14/03/2017
 * Time: 17:29
 */
require_once "classes/LabCreator.php";
if (isset($_POST["type"]) && isset($_POST['lab-name']) && isset($_POST['course-name']))
{
    $create = new LabCreator();

    if(isset($_POST["update"]))
        $create->updateLab($_POST["update"]);
    else
        $create->createLab();
}