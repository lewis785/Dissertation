<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 00:43
 */
require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../labs/Lab.php");
require_once (dirname(__FILE__)."/../students/Student.php");

class Marking extends Lab
{
    public function already_marked($studentID, $labQID)
    {
        $con = new ConnectDB();

        $check_already_marked = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($check_already_marked, "SELECT count(*) FROM lab_answers WHERE socRef = ? AND labQuestionRef = ?");
        mysqli_stmt_bind_param($check_already_marked, 'ii', $studentID, $labQID);
        mysqli_stmt_execute($check_already_marked);
        $result = mysqli_stmt_get_result($check_already_marked)->fetch_row();
        return $result[0] === 1;
    }

    public function getStudentAnswers($course, $lab, $student)
    {
        $con = new ConnectDB();

        $get_lab_question_ids = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_lab_question_ids, "SELECT questionID FROM lab_questions AS lq 
                                                    JOIN labs AS l ON lq.labRef = l.labID
                                                    JOIN courses AS c ON l.courseRef = c.courseID
                                                    WHERE c.courseName = ? AND l.labName = ?");
        mysqli_stmt_bind_param($get_lab_question_ids, 'ss', $course, $lab);
        mysqli_stmt_execute($get_lab_question_ids);
        $result= mysqli_stmt_get_result($get_lab_question_ids);


        $get_answers = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_answers, "SELECT la.answerNumber, la.answerBoolean, la.answerText FROM lab_answers AS la 
                                            JOIN students_on_courses AS soc ON la.socRef = soc.socID 
                                            JOIN user_details AS ud ON soc.student = ud.detailsId 
                                            WHERE labQuestionRef = ? AND ud.studentID = ? ");

        $answers = [];
        if(sizeof($result) > 0) {
            while ($question = $result->fetch_row()) {

                mysqli_stmt_bind_param($get_answers, 'is', $question[0], $student);
                mysqli_stmt_execute($get_answers);
                $answer = mysqli_stmt_get_result($get_answers);
                $rows = mysqli_num_rows($answer);
                $hasMark = ($rows === 1);
                if ($rows === 1) {
                    array_push($answers, $answer->fetch_row());
                } else {
                    break;
                }
            }

        }
        else
            $hasMark = false;

        mysqli_close($con->link);
        return json_encode(array("marked"=>$hasMark, "answers"=>$answers));
    }



    public function submitMark()
    {
        $con = new ConnectDB();
        $student = new Student();

        $qNum = 1;
        $answers = ($_POST["mark"]);
        $labID =    $this->get_lab_id($_SESSION["MARKING_COURSE"], $_SESSION["MARKING_LAB"]);
        $studentID = $student->get_studentID($_SESSION["MARKING_STUDENT"]);
        $questionID = $this->get_questionID($labID, $qNum);

        $already_present = false;
        if($this->already_marked($studentID, $questionID))
            $already_present = true;

        mysqli_autocommit($con->link, FALSE);                    //Sets up transaction for database insertion
        foreach($_POST["type"] as $type) {
            $successful = false;
            switch ($type) {                                       //Case statement checking what type each question is
                case "boolean":                                 //Inserts boolean type questions
                    if($answers[$qNum - 1] === "true")
                        $mark = $this->get_avalible_marks($con->link,$questionID);
                    else
                        $mark = 0;

                    $successful = $this->processAnswer($con->link, $already_present, $questionID, $studentID, NULL, $answers[$qNum - 1], NULL, $mark);
                    break;
                case "scale":                                   //Inserts scale type questions
                    $successful = $this->processAnswer($con->link, $already_present, $questionID, $studentID, $answers[$qNum - 1],NULL, NULL, $answers[$qNum - 1]);
                    break;
                case "value":                                   //Inserts value type questions
//                $successful = $this->processAnswer($con->link, $labID, $valueTypeID, $qNum, $questionText[$qNum - 1], NULL, $maxMarks[$minPos]);
                    break;
                default:
                    mysqli_rollback($con->link);                     //Undoes all inserts into the database during the transaction
                    $successful = false;                        //Sets successful to false
            }

            if (!$successful)                                   //Checks if insertion was successful
                break;                                          //If not ends the loop
            $qNum++;                                            //Increments the question number
            $questionID = $this->get_questionID($labID, $qNum);
        }
        mysqli_commit($con->link);
        mysqli_close($con->link);
    }



    private function processAnswer($link, $already_present, $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark)
    {

        $successful = false;
        if (!$already_present) {
            $successful = $this->insertAnswer($link, $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark);
        }
        else
        {
            $successful = $this->updateAnswer($link, $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark);
        }
        if (!$successful) {                                 //checks if insert or update failed
            mysqli_rollback($link);                         //Undoes all the inserts all ready done to the database
            return false;                                   //Returns false to show insert failed
        }
        return true;                                        //Returns true to show insert was successful
    }


    private function insertAnswer($link, $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark)
    {
        $insertAnswerQuery = 'INSERT INTO lab_answers (labQuestionRef, socRef, answerNumber, answerBoolean, answerText, mark) VALUES (?, ?, ?, ?, ?, ?)';
        $insertAnswer = mysqli_stmt_init($link);
        mysqli_stmt_prepare($insertAnswer, $insertAnswerQuery);
        mysqli_stmt_bind_param($insertAnswer, 'iiissi', $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark);

        return mysqli_stmt_execute($insertAnswer);
    }


    private function updateAnswer($link, $questionID, $studentID, $ansNum, $ansBool, $ansText, $mark)
    {
        $answerID = $this->getAnswerID($link, $questionID, $studentID);
        $updateAnswerQuery = 'UPDATE lab_answers SET answerNumber = ?, answerBoolean = ?, answerText = ?, mark = ? WHERE answerID = ?';
        $updateAnswer = mysqli_stmt_init($link);
        mysqli_stmt_prepare($updateAnswer, $updateAnswerQuery);
        mysqli_stmt_bind_param($updateAnswer, 'issii', $ansNum, $ansBool, $ansText, $mark, $answerID[0]);

        return  mysqli_stmt_execute($updateAnswer);
    }


    private function getAnswerID($link, $questionID, $studentID)
    {
        $get_answerID = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_answerID, "SELECT answerID FROM lab_answers WHERE labQuestionRef = ? AND socRef = ?");
        mysqli_stmt_bind_param($get_answerID, 'ii', $questionID, $studentID);
        mysqli_stmt_execute($get_answerID);
        return mysqli_stmt_get_result($get_answerID)->fetch_row();
    }


    private function get_avalible_marks($link, $questionID)
    {

        $get_max_mark = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_max_mark, "SELECT maxMark FROM lab_questions WHERE questionID = ?");
        mysqli_stmt_bind_param($get_max_mark, 'i', $questionID);
        mysqli_stmt_execute($get_max_mark);
        $result = mysqli_stmt_get_result($get_max_mark)->fetch_row();
        return $result[0];
    }


}

//$marking = new Marking();
//echo ($marking->getStudentAnswers("Software Development 1", "df", "H00100234"));