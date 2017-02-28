<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 18/02/2017
 * Time: 17:52
 */
include(dirname(__FILE__)."/../core/connection.php");

include(dirname(__FILE__)."/../courses/get_course_id.php");
include 'already_exists.php';

$successful = false;

if(isset($_POST["type"])){

    $qNum = 1;                                              //Question Number
    $minPos = 0;                                            //Array position of minMarks
    $maxPos = 0;                                            //Array position of maxMarks


    $questionText = $_POST["question"];                     //Array of all questions text
    $maxMarks = $_POST["max-value"];                        //Array of maximum marks for each question
    if(isset($_POST["min-value"]))
        $minMarks = $_POST["min-value"];                    //Array of minimum marks for related questions
    $types = $_POST["type"];                                //Array of each questions type

    $booleanTypeID = get_type_ID("boolean");                //ID value of boolean type question
    $scaleTypeID = get_type_ID("scale");                    //ID value of scale type questions
    $valueTypeID = get_type_ID("value");                    //ID value of value type questions
    $textTypeID = get_type_ID("text");                      //ID value of text type questions

    $course = get_course_id($_POST['course-name']);         //ID value of course
    $lab_name = $_POST['lab-name'];                         //Variable containing lab title
    $totalMark = calc_max_mark($maxMarks);                  //Calculates total available marks


    if (valid_input()) {                                    //Checks that input is valid before attempting to insert
        mysqli_autocommit($link, FALSE);                    //Sets up transaction for database insertion

        $labID = insert_lab_name($course, $lab_name, $totalMark);   //Inserts new lab in to labs table and gets the new ID it creates


        if ($labID !== false) {                                     //Checks that lab was successfully inserted

            foreach ($types as $t) {                                //Loops through each question by its type
                switch ($t) {                                       //Case statement checking what type each question is
                    case "boolean":                                 //Inserts boolean type questions
                        $successful = insert_question($labID, $booleanTypeID, $qNum, $questionText[$qNum - 1], NULL, $maxMarks[$maxPos]);
                        $maxPos++;
                        break;
                    case "scale":                                   //Inserts scale type questions
                        $successful = insert_question($labID, $scaleTypeID, $qNum, $questionText[$qNum - 1], $minMarks[$minPos], $maxMarks[$minPos]);
                        $maxPos++; $minPos++;
                        break;
                    case "value":                                   //Inserts value type questions
                        $successful = insert_question($labID, $valueTypeID, $qNum, $questionText[$qNum - 1], NULL, $maxMarks[$minPos]);
                        $maxPos++; $minPos++;
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
    }
    mysqli_commit($link);
}
mysqli_close($link);

if($successful)
    $redirect = "../../html/pages/labmanager.php";
else
    $redirect = "../../html/pages/labmaker.php";

header("Location: ".$redirect);                                 //Redirects to webpage





//Function checks that all inputs are valid and returns true / false accordingly
function valid_input()
{
    if (is_numeric($GLOBALS["course"])) {                   //Checks that valid course was selected
        if ($GLOBALS["lab_name"] !== "")                    //Checks if a lab name was entered
            if (!lab_already_exists($GLOBALS["course"],$GLOBALS['lab_name'])) {
                {
                    foreach ($GLOBALS["questionText"] as $q)         //Checks all questions have text in them
                        if ($q === "")
                            return false;                           //Returns false if question text is empty

                    foreach ($GLOBALS["maxMarks"] as $mark)          //Checks all questions have max mark set
                        if (!is_numeric($mark))
                            return false;                           //Returns false if a mark was not of type int

                    if(isset($_POST["min-value"])) {
                        foreach ($GLOBALS["minMarks"] as $mark)          //Checks all questions have min mark set
                            if (!is_numeric($mark))
                                return false;                           //Returns false if a mark was not of type int
                    }
                    echo "valid input";
                    return true;                                    //Returns true if all tests are passed
                }
            }
            else
                echo "already exists";
    }
    return false;                                           //Returns false if course is not of type int
}


//Function returns ID number of passed in type
function get_type_ID($typeName)
{
    $link = $GLOBALS['link'];
    $getTypeIDQuery = 'SELECT questionTypeID FROM question_types WHERE typeName = ?';
    $getTypeID = mysqli_stmt_init($link);
    mysqli_stmt_prepare($getTypeID, $getTypeIDQuery);
    mysqli_stmt_bind_param($getTypeID, 's',$typeName);
    mysqli_stmt_execute($getTypeID);                                //Executes the statement
    $result = mysqli_stmt_get_result($getTypeID)->fetch_row();      //Retrieves the first rows results
    return $result[0];                                              //Returns result value
}


//Calculates total number of marks and returns value
function calc_max_mark($questions)
{
    $totalMark = 0;                         //Sets total mark to 0
    foreach ($questions as $mark)           //For loop of max mark for each question
        $totalMark += $mark;                //Adds mark to total mark
    return $totalMark;                      //Returns total mark
}


//Inserts the name of the lab and course into the database and returns its ID number or false if it failed
function insert_lab_name($course, $name, $max)
{
    $link = $GLOBALS['link'];
    $insertLab = mysqli_stmt_init($link);                                                                   //Initialises prepared statement
    mysqli_stmt_prepare($insertLab, "INSERT INTO labs (courseRef, labName, maxMark) VALUES (?, ?, ?)");     //Prepares the statement
    mysqli_stmt_bind_param($insertLab, 'isi', $course, $name, $max);                                        //Binds parameter

    if (! mysqli_stmt_execute($insertLab) ){    //Executes statement and check if it failed
        mysqli_rollback($link);                 //Undoes all inserts into the database
        echo "Error Inserting Lab Name";
        return false;                           //Returns false to show insert failed
    }
    return mysqli_insert_id($link);             //Returns the ID number created by inserting
}


//Inserts question into the lab_questions table
function insert_question($labID, $type, $number, $question, $minValue, $maxValue)
{
    $link = $GLOBALS['link'];
    $insertQuestionQuery = 'INSERT INTO lab_questions (labRef, questionType, questionNumber, question, minMark, maxMark) VALUES (?, ?, ?,?, ?, ?)';
    $insertQuestion = mysqli_stmt_init($link);
    mysqli_stmt_prepare($insertQuestion, $insertQuestionQuery);
    mysqli_stmt_bind_param($insertQuestion, 'iiisii',$labID, $type, $number, $question, $minValue, $maxValue);


    if (! mysqli_stmt_execute($insertQuestion) ){       //Runs the insertion and checks if it failed
        mysqli_rollback($link);                         //Undoes all the inserts all ready done to the database
        echo "Error Inserting";
        return false;                                   //Returns false to show insert failed
    }
    return true;                                        //Returns true to show insert was successful
}