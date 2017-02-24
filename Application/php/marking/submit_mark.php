<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 13:10
 */


include(dirname(__FILE__)."/../core/connection.php");

if(isset($_POST["mark"]))
{
    print_r($_POST["mark"]);
    print_r($_POST["type"]);
    $qnum = 1;


    foreach($type as $_POST["type"]) {
        switch ($t) {                                       //Case statement checking what type each question is
            case "boolean":                                 //Inserts boolean type questions
                $successful = insert_answer($labID, $booleanTypeID, $qNum, $questionText[$qNum - 1], NULL, $maxMarks[$maxPos]);
                $maxPos++;
                break;
            case "scale":                                   //Inserts scale type questions
                $successful = isnert_answer($labID, $scaleTypeID, $qNum, $questionText[$qNum - 1], $minMarks[$minPos], $maxMarks[$minPos]);
                $maxPos++;
                $minPos++;
                break;
            case "value":                                   //Inserts value type questions
                $successful = insert_answer($labID, $valueTypeID, $qNum, $questionText[$qNum - 1], NULL, $maxMarks[$minPos]);
                $maxPos++;
                $minPos++;
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