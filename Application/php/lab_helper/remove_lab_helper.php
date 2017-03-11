<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 10/03/2017
 * Time: 20:50
 */

if (isset($_POST["course"]) && isset($_POST["student"])) {
    require_once "LabHelper.php";

    $labHelper = new LabHelper();
    echo($labHelper->removeLabHelper($_POST["course"], $_POST["student"]));
}