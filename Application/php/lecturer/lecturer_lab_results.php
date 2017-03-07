<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:54
 */


require_once (dirname(__FILE__) . "/../core/Security.php");
require_once "LecturerResults.php";

$security = new Security();
if($security->has_access_level("lecturer")) {
    $results = new LecturerResults();
    echo ($results->displayLabResults());

}
