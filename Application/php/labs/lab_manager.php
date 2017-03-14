<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 11/03/2017
 * Time: 19:51
 */

require_once "classes/LabManager.php";

if(isset($_POST["action"]) && isset($_POST["labID"]))
{
    $manage= new LabManager();
    $action = $_POST["action"];


    if($action==="export")
        $manage->exportLabResults();
    elseif($action==="edit")
        echo($manage->editLab());
    elseif($action==="delete")
        echo($manage->deleteLab());

}