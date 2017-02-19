<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 17/02/2017
 * Time: 17:45
 */
include(dirname(__FILE__)."/../core/connection.php");
include(dirname(__FILE__)."/../core/check_access_level.php");

if(has_access_level($link,"lecturer"))
{
    echo "lecturer";

    $get_courses = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_courses, "SELECT c.courseName FROM user_details AS d 
                                              JOIN user_login AS l ON d.detailsId = l.userID 
                                              JOIN course_lecturer AS cl ON l.userID = cl.lecturer 
                                              JOIN courses AS c ON cl.course = c.courseID 
                                              WHERE l.username = ?");

    mysqli_stmt_bind_param($get_courses, 's', $_SESSION["username"]);
    mysqli_stmt_execute($get_courses);
    $result = mysqli_stmt_get_result($get_courses);

    $output = "";
    foreach($result as $row){
        foreach($row as $course) {
            echo "<option value='".$course."'>".$course."</option>";
        }
    }
}
else
    {
    echo "not a lecturer";
}

mysqli_close($link);

