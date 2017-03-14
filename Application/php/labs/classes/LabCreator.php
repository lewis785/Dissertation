<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 14:42
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once(dirname(__FILE__) . "/../../courses/classes/Courses.php");
require_once "LabChecks.php";
require_once "Lab.php";

class LabCreator extends LabChecks
{

    private $link;
    private $courses;

    private $questions;
    private $max_marks;
    private $min_marks;
    private $types;
    private $visibility;
    private $course;
    private $lab_name;

    function __construct()
    {
        $this->courses = new Courses();

        $this->questions = isset($_POST["question"]) ? $_POST["question"] : null;
        $this->max_marks = isset($_POST["max-value"]) ? $_POST["max-value"] : null;
        $this->min_marks = isset($_POST["min-value"]) ? $_POST["min-value"] : null;
        $this->types = isset($_POST["type"]) ? $_POST["type"] : null;
        $this->visibility = isset($_POST["visibility"]) ? $_POST["visibility"] : null;

        $this->course = $this->courses->get_course_id($_POST['course-name']);         //ID value of course
        $this->lab_name = $_POST['lab-name'];                         //Variable containing lab title
    }

    public function displayInputs()
    {
        print_r($this->questions);
        print_r($this->max_marks);
        print_r($this->min_marks);
        print_r($this->types);
        print_r($this->visibility);
    }


    public function createLab()
    {

        $successful = false;
        $con = new ConnectDB();
        $this->link = $con->link;

        $qNum = 1;                                              //Question Number
        $minPos = 0;                                            //Array position of minMarks
        $maxPos = 0;                                            //Array position of maxMarks


        $booleanTypeID = $this->get_type_ID("boolean");                //ID value of boolean type question
        $scaleTypeID = $this->get_type_ID("scale");                    //ID value of scale type questions
        $valueTypeID = $this->get_type_ID("value");                    //ID value of value type questions
        $textTypeID = $this->get_type_ID("text");                      //ID value of text type questions



        if ($this->valid_input()) {                                    //Checks that input is valid before attempting to insert
            mysqli_autocommit($con->link, FALSE);                    //Sets up transaction for database insertion

            $labID = $this->insert_lab_name($this->course, $this->lab_name);   //Inserts new lab in to labs table and gets the new ID it creates

            if ($labID !== false) {                                     //Checks that lab was successfully inserted

                foreach ($this->types as $t) {                                //Loops through each question by its type
                    switch ($t) {                                       //Case statement checking what type each question is
                        case "boolean":                                 //Inserts boolean type questions
                            $successful = $this->insert_question($labID, $booleanTypeID, $qNum, $this->questions[$qNum - 1], NULL, $this->max_marks[$maxPos], $this->visibility[$qNum - 1]);
                            $maxPos++;
                            break;
                        case "scale":                                   //Inserts scale type questions
                            $successful = $this->insert_question($labID, $scaleTypeID, $qNum, $this->questions[$qNum - 1], $this->min_marks[$minPos], $this->max_marks[$maxPos], $this->visibility[$qNum - 1]);
                            $maxPos++;
                            $minPos++;
                            break;
                        case "value":                                   //Inserts value type questions
                            $successful = $this->insert_question($labID, $valueTypeID, $qNum, $this->questions[$qNum - 1], NULL, $this->max_marks[$minPos], $this->visibility[$qNum - 1]);
                            $maxPos++;
                            $minPos++;
                            break;
                        case "text":                                   //Inserts value type questions
                            $successful = $this->insert_question($labID, $textTypeID, $qNum, $this->questions[$qNum - 1], 0, $this->max_marks[$maxPos], $this->visibility[$qNum - 1]);
                            $maxPos++;
                            $minPos++;
                            break;
                        default:
                            echo "default";                             //Default if type doesn't exist
                            mysqli_rollback($con->link);                     //Undoes all inserts into the database during the transaction
                            $successful = false;                        //Sets successful to false
                    }

                    if (!$successful)                                   //Checks if insertion was successful
                        break;                                          //If not ends the loop
                    $qNum++;                                            //Increments the question number
                }
            }
        }


        mysqli_commit($con->link);
        mysqli_close($con->link);

        if ($successful)
            $redirect = "../../html/pages/labmanager.php";
        else
            $redirect = "../../html/pages/labmaker.php";

        header("Location: " . $redirect);                                 //Redirects to webpage
    }


    public function updateLab($labID)
    {
        $Lab = new Lab();
        $Course  = new CourseChecks();
        $con = new ConnectDB();
        $this->link = $con->link;

        $coursename = $this->courses->courseFromLabID($labID);
        $maxPos = 0;
        $minPos = 0;

        if($Course->is_lecturer_of_course($coursename)) {
            mysqli_autocommit($con->link, FALSE);                    //Sets up transaction for database insertion
            $successful = false;
            foreach ($this->types as $index => $t) {                                //Loops through each question by its type
                $qID = $Lab->get_questionID($labID,$index+1);
                switch ($t) {                                       //Case statement checking what type each question is
                    case "boolean":                                 //Inserts boolean type questions
                        $successful = $this->updateQuestion($qID, $this->get_type_ID("boolean"), $this->questions[$index], NULL, $this->max_marks[$maxPos], $this->visibility[$index]);
                        $maxPos++;
                        break;
                    case "scale":                                   //Inserts scale type questions
                        $successful = $this->updateQuestion($qID, $this->get_type_ID("boolean"), $this->questions[$index], $this->min_marks[$minPos], $this->max_marks[$maxPos], $this->visibility[$index]);
                        $maxPos++;
                        $minPos++;
                        break;
                    case "value":                                   //Inserts value type questions
                        $successful = $this->updateQuestion($qID, $this->get_type_ID("boolean"), $this->questions[$index], NULL, $this->max_marks[$minPos], $this->visibility[$index]);
                        $maxPos++;
                        $minPos++;
                        break;
                    case "text":                                   //Inserts value type questions
                        $successful = $this->updateQuestion($qID, $this->get_type_ID("boolean"), $this->questions[$index], 0, $this->max_marks[$maxPos], $this->visibility[$index]);
                        $maxPos++;
                        $minPos++;
                        break;
                    default:
                        echo "default";                             //Default if type doesn't exist
                        mysqli_rollback($con->link);                     //Undoes all inserts into the database during the transaction
                        $successful = false;                        //Sets successful to false
                }
                if(!$successful)
                    break;

            }

            mysqli_commit($con->link);
            mysqli_close($con->link);
            $redirect = "../../html/pages/labmanager.php";
            header("Location: " . $redirect);                                 //Redirects to webpage
        }
    }

//Function checks that all inputs are valid and returns true / false accordingly
    private function valid_input()
    {
        if (is_numeric($this->course)) {                   //Checks that valid course was selected
            if ($this->lab_name !== "")                    //Checks if a lab name was entered
                if (!$this->lab_already_exists($this->course, $this->lab_name)) {
                    {
                        foreach ($this->questions as $q)         //Checks all questions have text in them
                            if ($q === "")
                                return false;                           //Returns false if question text is empty

                        foreach ($this->max_marks as $mark)          //Checks all questions have max mark set
                            if (!is_numeric($mark))
                                return false;                           //Returns false if a mark was not of type int

                        if (sizeof($this->min_marks) > 0) {
                            foreach ($this->min_marks as $mark)          //Checks all questions have min mark set
                                if (!is_numeric($mark))
                                    return false;                           //Returns false if a mark was not of type int
                        }
                        return true;                                    //Returns true if all tests are passed
                    }
                } else
                    return false;                               //Returns false if a lab already exists with the inputted name
        }
        return false;                                           //Returns false if course is not of type int
    }


    //Returns true if name of lab is already used by the course



//Function returns ID number of passed in type
    private function get_type_ID($typeName)
    {
        $getTypeIDQuery = 'SELECT questionTypeID FROM question_types WHERE typeName = ?';
        $getTypeID = mysqli_stmt_init($this->link);
        mysqli_stmt_prepare($getTypeID, $getTypeIDQuery);
        mysqli_stmt_bind_param($getTypeID, 's', $typeName);
        mysqli_stmt_execute($getTypeID);                                //Executes the statement
        $result = mysqli_stmt_get_result($getTypeID)->fetch_row();      //Retrieves the first rows results
        return $result[0];                                              //Returns result value
    }

//Inserts the name of the lab and course into the database and returns its ID number or false if it failed
    private function insert_lab_name($course, $name)
    {
        $state = "false";
        $insertLab = mysqli_stmt_init($this->link);                                                                   //Initialises prepared statement
        mysqli_stmt_prepare($insertLab, "INSERT INTO labs (courseRef, labName, canMark) VALUES (?, ?, ?)");     //Prepares the statement
        mysqli_stmt_bind_param($insertLab, 'iss', $course, $name, $state);                                        //Binds parameter

        if (!mysqli_stmt_execute($insertLab)) {    //Executes statement and check if it failed
            mysqli_rollback($this->link);                 //Undoes all inserts into the database
            echo "Error Inserting Lab Name";
            return false;                           //Returns false to show insert failed
        }
        return mysqli_insert_id($this->link);             //Returns the ID number created by inserting
    }


//Inserts question into the lab_questions table
    private function insert_question($labID, $type, $number, $question, $minValue, $maxValue, $visible)
    {
        $insertQuestionQuery = 'INSERT INTO lab_questions (labRef, questionType, questionNumber, question, minMark, maxMark, private) VALUES (?, ?, ?,?, ?, ?, ?)';
        $insertQuestion = mysqli_stmt_init($this->link);
        mysqli_stmt_prepare($insertQuestion, $insertQuestionQuery);
        mysqli_stmt_bind_param($insertQuestion, 'iiisiis', $labID, $type, $number, $question, $minValue, $maxValue, $visible);


        if (!mysqli_stmt_execute($insertQuestion)) {       //Runs the insertion and checks if it failed
            mysqli_rollback($this->link);                         //Undoes all the inserts all ready done to the database
            echo "Error Inserting";
            return false;                                   //Returns false to show insert failed
        }
        return true;
    }


    private function updateQuestion($question_id, $type, $question, $minValue, $maxValue, $visible)
    {
        $insertQuestionQuery = 'UPDATE lab_questions SET questionType = ?, question = ?, minMark = ?, maxMark = ?, private =? WHERE questionID = ?';
        $insertQuestion = mysqli_stmt_init($this->link);
        mysqli_stmt_prepare($insertQuestion, $insertQuestionQuery);
        mysqli_stmt_bind_param($insertQuestion, 'isiisi',  $type, $question, $minValue, $maxValue, $visible, $question_id);

        if (!mysqli_stmt_execute($insertQuestion)) {       //Runs the insertion and checks if it failed
            mysqli_rollback($this->link);                         //Undoes all the inserts all ready done to the database
            echo "Error Inserting";
            return false;                                   //Returns false to show insert failed
        }
        return true;
    }

}