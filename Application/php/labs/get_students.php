<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 17:32
 */
include(dirname(__FILE__)."/../courses/course_checks.php");


if(isset($_POST["lab"])) {
    get_students_buttons($_POST["lab"]);
}

function get_students_buttons($lab)
{
    include(dirname(__FILE__)."/../core/connection.php");


    if (can_mark_course($link,$_SESSION["MARKING_COURSE"]))
    {
        $_SESSION["MARKING_LAB"] = $lab;
        $result = get_students($_SESSION["MARKING_COURSE"]);
        $buttons = "";
        while ($student = $result->fetch_row()) {
            $buttons .= "<div class='col-md-6 col-md-offset-3'>
                      <button class='btn btn-success' id='btn-student' onclick='display_schema_for(\"" . $student[2] . "\")'>" . $student[0] ." ". $student[1] . "</button>
                     </div>";
        }
        echo json_encode(array('successful' => true, 'buttons' => $buttons));
    }
    else
        echo json_encode(array('successful'=>false));

    mysqli_close($link);
}


function get_students($course)
{
    include(dirname(__FILE__)."/../core/connection.php");

    if (can_mark_course($link,$_SESSION["MARKING_COURSE"]))
    {
        $get_students = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_students, "SELECT d.firstname, d.surname, d.studentID from students_on_courses as soc 
                                        JOIN user_details AS d ON soc.student = d.detailsId 
                                        JOIN courses AS c ON soc.course = c.courseID 
                                        WHERE c.courseName = ? 
                                        ORDER BY d.surname, d.firstname");
        mysqli_stmt_bind_param($get_students, 's', $course);
        mysqli_stmt_execute($get_students);
        return mysqli_stmt_get_result($get_students);
    }

    mysqli_close($link);
}