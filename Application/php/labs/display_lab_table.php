<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 26/02/2017
 * Time: 22:29
 */

require_once "classes/LabManager.php";


if( isset($_POST["displayTable"]) )
{
    if ($_POST["displayTable"] === "manage-table")
    {
        $display = new LabManager();
        echo ($display->labManagementTable());
    }
}