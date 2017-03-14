<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 27/02/2017
 * Time: 20:05
 */

require_once "classes/LabMaker.php";

if(isset($_POST["labID"]))
{
    $maker = new LabMaker();
    echo($maker->displayEditableLab($_POST["labID"]));
}
//$maker = new LabMaker();
//$maker->displayEditableLab(4);