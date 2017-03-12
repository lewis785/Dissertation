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
    $id = $_POST["id"];
    $qnum = $_POST["qnum"];

    switch ($_POST["type"])
    {
        case "boolean":
            echo $maker->booleanQuestion($id, $qnum);
            break;
        case "scale":
            echo $maker->scaleQuestion($id, $qnum);
            break;
        case "text":
            echo $maker->textQuestion($id, $qnum);
            break;
        default:
            echo json_encode(array("question"=>"<div>Question Type does not exists</div>"));

    }

}