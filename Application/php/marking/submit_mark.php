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



if(isset($_POST["mark"]))
{
    $answers = ($_POST["mark"]);
    $qNum = 1;

    $labID =    get_lab_id(get_course_id($_SESSION["MARKING_COURSE"]), $_SESSION["MARKING_LAB"]);
    $studentID = get_studentID($_SESSION["MARKING_STUDENT"]);

    mysqli_autocommit($link, FALSE);                    //Sets up transaction for database insertion
    foreach($_POST["type"] as $type) {
        $questionID = get_questionID($labID, $qNum);
        switch ($type) {                                       //Case statement checking what type each question is
            case "boolean":                                 //Inserts boolean type questions
                $successful = insert_answer($questionID, $studentID, NULL, $answers[$qNum - 1], NULL, "-1");
                break;
            case "scale":                                   //Inserts scale type questions
//                $successful = isnert_answer($labID, $scaleTypeID, $qNum, $questionText[$qNum - 1], $minMarks[$minPos], $maxMarks[$minPos]);
                break;
            case "value":                                   //Inserts value type questions
//                $successful = insert_answer($labID, $valueTypeID, $qNum, $questionText[$qNum - 1], NULL, $maxMarks[$minPos]);
                break;
            default:
                echo "default";                             //Default if type doesn't exist
                mysqli_rollback($link);                     //Undoes all inserts into the database during the transaction
                $successful = false;                        //Sets successful to false
        }

        if (!$successful)                                   //Checks if insertion was successful
            break;                                          //If not ends the loop
        $qNum++;                                            //Increments the question number
    }
}

mysqli_close($link);



function get_studentID($student)
{
    $link = $GLOBALS["link"];

    $get_studentsID = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_studentsID, "SELECT s.socID FROM students_on_courses AS s 
                                          JOIN user_details as d ON s.student = d.detailsID 
                                          WHERE d.studentID = ?");
    mysqli_stmt_bind_param($get_studentsID, 's', $student);
    mysqli_stmt_execute($get_studentsID);
    $result = mysqli_stmt_get_result($get_studentsID)->fetch_row();

    return $result[0];
}


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


function insert_answer($questionID, $studentID, $ansNum, $ansBool, $ansText, $mark)
{
    $link = $GLOBALS["link"];
    echo "qid: ".$questionID ." sid: " .$studentID ." qnum: ". $ansNum ." ansB: ". $ansBool . "ansT: ".$ansText ." mark: ". $mark;

    $insertAnswerQuery = 'INSERT INTO lab_answers (labQuestionRef, socRef, answerNumber, answerBoolean, answerText, mark) VALUES (?, ?, ?, ?, ?, ?)';
    $insertAnswer = mysqli_stmt_init($link);
    mysqli_stmt_prepare($insertAnswer, $insertAnswerQuery);
    mysqli_stmt_bind_param($insertAnswer, 'iisssi',$questionID, $studentID, $ansNum, $ansBool, $ansText, $mark);


    if (! mysqli_stmt_execute($insertAnswer) ){       //Runs the insertion and checks if it failed
        mysqli_rollback($link);                         //Undoes all the inserts all ready done to the database
        echo "Error Inserting";
        return false;                                   //Returns false to show insert failed
    }
    return true;                                        //Returns true to show insert was successful

}