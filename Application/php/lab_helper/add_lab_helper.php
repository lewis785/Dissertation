<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 10/03/2017
 * Time: 21:59
 */

if (isset($_POST["course"]) && isset($_POST["student"])) {
    require_once "classes/LabHelper.php";

    $labHelper = new LabHelper();
    echo($labHelper->addLabHelper($_POST["course"], $_POST["student"]));
}