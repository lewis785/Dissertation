<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:54
 */


require_once(dirname(__FILE__) . "/../core/classes/Security.php");
require_once "classes/LecturerResults.php";

$security = new Security();
if($security->hasAccessLevel("lecturer")) {
    $results = new LecturerResults();
    echo ($results->displayLabResults());

}
