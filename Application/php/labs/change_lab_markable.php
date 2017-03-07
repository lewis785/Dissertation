<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 28/02/2017
 * Time: 18:23
 */

require_once "LabManager.php";

if(isset($_POST["labID"]) && isset($_POST["newState"]))
{
    $manager = new LabManager();
    echo($manager->changeMarkable());
}