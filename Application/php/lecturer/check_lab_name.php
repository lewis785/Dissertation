<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 04/03/2017
 * Time: 23:11
 */

require_once "classes/LecturerLab.php";

if(isset($_POST["course"]) && isset($_POST["lab"])) {

    $lab = new LecturerLab();
    echo($lab->checkLabName($_POST["course"], $_POST["lab"]));

}