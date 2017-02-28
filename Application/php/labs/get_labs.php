<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 16:02
 */


if(isset($_POST["course"])) {
    get_labs_buttons($_POST["course"]);
}

function get_labs($course)
{
    include(dirname(__FILE__)."/../core/connection.php");
    require_once(dirname(__FILE__)."/../courses/course_checks.php");
    require_once(dirname(__FILE__)."/../core/check_access_level.php");

    if(has_access_level($link,"lecturer"))                                  //Checks if user has lecturer access
        $limit = ["true","false"];                                          //Allows user to see all lab on course (Looks bad but works)
    else
        $limit = ["true","true"];                                           //Allows user to only see markable labs (Looks bad but works)


    if (can_mark_course($link,$course)) {
        $get_labs = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_labs, "SELECT l.labName FROM labs AS l 
                                        JOIN courses AS c ON l.courseRef = c.courseID 
                                        WHERE c.courseName = ? AND (l.canMark = ? OR l.canMark = ?) ORDER BY l.labID");
        mysqli_stmt_bind_param($get_labs, 'sss', $course , $limit[0], $limit[1]);
        mysqli_stmt_execute($get_labs);
        $result = mysqli_stmt_get_result($get_labs);
        $labsArray = [];
        while($lab = $result->fetch_row())
            array_push($labsArray, $lab[0]);
        mysqli_close($link);
        return $labsArray;
    }
}



function get_labs_buttons($course)
{

        include(dirname(__FILE__)."/../core/connection.php");
        $_SESSION["MARKING_COURSE"] = $course;
        mysqli_close($link);

        $buttons = "";
        $result = get_labs($course);
        foreach($result as $lab)
        {
            $buttons .= "<div class='col-md-6 col-md-offset-3'>
                          <button class='btn btn-success' id='btn-course' onclick='display_students_for(\"".$lab."\")'>". $lab."</button>
                         </div>";
        }
        echo json_encode(array('successful'=>true, 'buttons'=>$buttons));

}