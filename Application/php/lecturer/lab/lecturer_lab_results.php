<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/03/2017
 * Time: 00:54
 */

include (dirname(__FILE__)."/../../core/connection.php");
require_once (dirname(__FILE__)."/../../core/check_access_level.php");

if(has_access_level($link,"lecturer")) {
    require_once(dirname(__FILE__) . "/../../labs/get_labs.php");
    require_once(dirname(__FILE__) . "/../../labs/get_lab_id.php");
    require_once(dirname(__FILE__) . "/../../labs/get_students.php");
    require_once(dirname(__FILE__) . "/../../labs/lab_total_mark.php");
    require_once(dirname(__FILE__) . "/../../courses/get_courses.php");


    $courses = get_courses();
    $resultsTable = "";

    $id = 0;
    foreach ($courses as $course) {
        $labs = get_labs($course);
        $students = get_students($course);
        $studentSelector = create_student_selector($students, $course);

        $resultsTable .= "<div class='col-md-12 results-course-row'><div class='col-md-6 col-md-offset-3'>$course</div></div><ul class='labs-list'>";


        $length = (sizeof($labs) - 1);
        foreach ($labs as $lab) {
            $rowNumOdd = true;
            $resultsTable.= get_lab_summary($link, $course, $lab[0], $id);

            $statsArea = "<div id='stats-area' class='col-md-12'></div>";
            $studentArea = "<div class='col-md-8' id='student-info'>
                                <div class='col-md-12' id='student-name'>No Student Selected</div>
                                <div class='col-md-12' id='student-stats'>
                                    <div class='col-md-6' id='stats-mark'></div>
                                    <div class='col-md-6' id='stats-percentage'></div>
                                </div>
                                <div class='col-md-12' id='student-answers'></div>
                             </div>";

            $id++;
            $resultsTable .= $statsArea . $studentSelector . $studentArea . "</li>";
        }
        $resultsTable .= "</ul>";


    }
    echo $resultsTable;
}
else
    echo "You do not have the required access level to run this function";

mysqli_close($link);


function create_student_selector($studentArray, $course){

    $selector = "<div class='col-md-4'><label>Student select list:</label>
                   <select class='col-md-12 student-selector form-control' size='8' multiple onchange='display_student_result(this, \"$course\", this.value)'>
                   <option selected value='no-selection'>No Student</option>";
    while ($student = $studentArray->fetch_row())
    {
        $selector.="<option value='$student[2]'>$student[0] $student[1]</option>";
    }
    $selector .= "</select></div>";
    return $selector;
}



function get_lab_summary($link, $course, $lab, $id)
{

//    onclick='change_div_size($id)
    $output ="<li class='col-md-12 results-lab-row' id='result-row-$id' '>
                            <div class='result-align-center result-summary col-md-12'>
                                <div id='result-row-arrow-$id' class='result-align-center col-md-1  glyphicon glyphicon-triangle-right'></div>
                                <div class='col-md-3 col-md-offset-1'>Lab Name: <span id='lab-name'>$lab</span></div>
                            </div>";

    return $output;
}
