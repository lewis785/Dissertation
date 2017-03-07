<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 00:40
 */
require_once "LecturerCourses.php";

$manager = new LecturerCourses();
echo ($manager->displayCourseManager());