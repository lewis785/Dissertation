<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 12/03/2017
 * Time: 20:05
 */
require_once "LabMaker.php";
if(isset($_POST["type"]) && isset($_POST["id"]) && isset($_POST["qnum"]))
{
    $maker = new LabMaker();
    $type = $_POST["type"];
    $id = $_POST["id"];
    $qnum = $_POST["qnum"];

    if(is_numeric($id) && is_numeric($qnum))
        echo($maker->createQuestion($type,$id,$qnum));

}