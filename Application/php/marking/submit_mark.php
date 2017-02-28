<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 13:10
 */


include(dirname(__FILE__)."/../core/connection.php");
include(dirname(__FILE__)."/../courses/get_course_id.php");
include(dirname(__FILE__)."/../labs/get_lab_id.php");

require "check_if_marked.php";                              //Includes the already_marked() function to check if student has already been marked

require (dirname(__FILE__)."/../students/get_student_id.php");



if(isset($_POST["mark"]))
{
    $qNum = 1;
    $answers = ($_POST["mark"]);
    $labID =    get_lab_id($link,$_SESSION["MARKING_COURSE"], $_SESSION["MARKING_LAB"]);
    $studentID = get_studentID($_SESSION["MARKING_STUDENT"]);
    $questionID = get_questionID($labID, $qNum);

    $already_present = false;
    if(already_marked($studentID, $questionID))
        $already_present = true;

    mysqli_autocommit($link, FALSE);                    //Sets up transaction for database insertion
    foreach($_POST["type"] as $type) {
        switch ($type) {                                       //Case statement checking what type each question is
            case "boolean":                                 //Inserts boolean type questions
                $successful = insert_answer($already_present, $questionID, $studentID, NULL, $answers[$qNum - 1], NULL, "-1");
                break;
            case "scale":                                   //Inserts scale type questions
                $successful = insert_answer($already_present, $questionID, $studentID, $answers[$qNum - 1],NULL, NULL, $answers[$qNum - 1]);
                break;
            case "value":                                   //Inserts value type questions
//                $successful = insert_answer($labID, $valueTypeID, $qNum, $questionText[$qNum - 1], NULL, $maxMarks[$minPos]);
                break;
            default:
                mysqli_rollback($link);                     //Undoes all inserts into the database during the transaction
                $successful = false;                        //Sets successful to false
        }

        if (!$successful)                                   //Checks if insertion was successful
            break;                                          //If not ends the loop
        $qNum++;                                            //Increments the question number
        $questionID = get_questionID($labID, $qNum);
    }
    mysqli_commit($link);
}
else{
    echo json_encode("ERROR");
}
mysqli_close($link);





function get_questionID($labID, $questionNum)
{
    $link = $GLOBALS["link"];

    $get_questionID = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_questionID, "SELECT questionID FROM lab_questions WHERE labRef = ? AND questionNumber = ?");
    mysqli_stmt_bind_param($get_questionID, 'ii', $labID, $questionNum);
    mysqli_stmt_execute($get_questionID);
    $result = mysqli_stmt_get_result($get_questionID)->fetch_row();
    return $result[0];
}


function insert_answer($already_present, $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark)
{
    $link = $GLOBALS["link"];
    $successful = false;
//    echo "present: ".$already_present."qid: ".$questionID ." sid: " .$studentID ." qnum: ". $ansNum ." ansB: ". $ansBool . "ansT: ".$ansText ." mark: ". $mark;
    if (!$already_present) {
        require"insert_answer.php";
    }
    else
    {
        require"update_answer.php";
    }
    if (!$successful) {                                 //checks if insert or update failed
        mysqli_rollback($link);                         //Undoes all the inserts all ready done to the database
        return false;                                   //Returns false to show insert failed
    }
    return true;                                        //Returns true to show insert was successful
}