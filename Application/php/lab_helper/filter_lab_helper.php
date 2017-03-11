<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 10/03/2017
 * Time: 22:34
 */

if (isset($_POST["course"]) && isset($_POST["filter"])) {
    require_once (dirname(__FILE__)."/../admin/AdminForms.php");

    $admin = new AdminForms();
    echo($admin->filterHelpersTable($_POST["course"], $_POST["filter"]));
}