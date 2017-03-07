<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 17:32
 */
include(dirname(__FILE__)."/../courses/course_checks.php");

require_once "LabStudents.php";

if(isset($_POST["type"])) {

    $student = new LabStudents();
    echo($student->get_students_buttons());

}

//get_students_buttons("df");

////Returns all students on a course as clickable buttons
//function get_students_buttons($lab)
//{
//    include(dirname(__FILE__)."/../core/connection.php");
//    require_once(dirname(__FILE__)."/../marking/check_if_marked.php");
//    require_once(dirname(__FILE__)."/../labs/get_lab_id.php");
//    require_once(dirname(__FILE__)."/../labs/get_question_id.php");
//    require_once (dirname(__FILE__)."/../students/get_student_id.php");
//    require_once (dirname(__FILE__)."/../students/evalute_student_marks.php");
//
//    if (can_mark_course($link,$_SESSION["MARKING_COURSE"]))
//    {
//        $_SESSION["MARKING_LAB"] = $lab;
//        $courseName = $_SESSION["MARKING_COURSE"];
//        $labID = get_questionID($link,get_lab_id($link, $courseName, $lab), 1);
//
//        $result = get_students($courseName);
//        $buttons = "";
//        while ($student = $result->fetch_row()) {
//
//            $buttonType = button_style($link, $student[2],$labID, $lab, $courseName);
//            $buttons .= "<div class='col-md-6 col-md-offset-3 col-sm-12'>
//                      <button class='".$buttonType."' id='btn-student' onclick='display_schema_for(\"" . $student[2] . "\")'>" . $student[0] ." ". $student[1] . "</button>
//                     </div>";
//        }
//        echo json_encode(array('successful' => true, 'buttons' => $buttons));
//    }
//    else
//        echo json_encode(array('successful'=>false));
//
//    mysqli_close($link);
//}
//
//
//function button_style($link, $matric,$qID, $labName, $courseName)
//{
//    if(already_marked($link, get_studentID($link,$matric), $qID)) {
//        if(has_full_marks($matric,$courseName, $labName))
//            return "btn btn-success";
//        elseif (has_no_marks($matric,$courseName, $labName))
//            return "btn btn-danger";
//        return "btn btn-warning";
//    }
//    else
//        return "btn btn-info";
//}
//
//
//function get_students($course)
//{
//    include(dirname(__FILE__)."/../core/connection.php");
//
//    if (can_mark_course($link,$course))
//    {
//        $get_students = mysqli_stmt_init($link);
//        mysqli_stmt_prepare($get_students, "SELECT d.firstname, d.surname, d.studentID from students_on_courses as soc
//                                        JOIN user_details AS d ON soc.student = d.detailsId
//                                        JOIN courses AS c ON soc.course = c.courseID
//                                        WHERE c.courseName = ?
//                                        ORDER BY d.surname, d.firstname");
//        mysqli_stmt_bind_param($get_students, 's', $course);
//        mysqli_stmt_execute($get_students);
//        return mysqli_stmt_get_result($get_students);
//    }
//
//    mysqli_close($link);
//}