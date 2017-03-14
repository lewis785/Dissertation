<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 16:46
 */

require_once "classes/CourseButtons.php";
$courses = new CourseButtons();
echo $courses->courses_dropdown();