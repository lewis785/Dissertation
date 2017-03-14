<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 11/03/2017
 * Time: 00:59
 */

if (isset($_POST["course"]) && isset($_POST["filter"])) {
    require_once(dirname(__FILE__) . "/../admin/classes/AdminForms.php");

    $admin = new AdminForms();
    echo($admin->filterStudentTable($_POST["course"], $_POST["filter"]));
}