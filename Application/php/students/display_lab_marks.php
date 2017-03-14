<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:54
 */

require_once "classes/StudentResults.php";

$result = new StudentResults();
echo $result->getStudentResults();