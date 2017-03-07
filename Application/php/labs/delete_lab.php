<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 27/02/2017
 * Time: 20:05
 */


require_once "LabManager.php";

if(isset($_POST["labID"]))
{
    $manage = new LabManager();
    echo($manage->deleteLab());
}
else
    echo json_encode(array("success"=>false));
