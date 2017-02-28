<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 27/02/2017
 * Time: 20:05
 */



if(isset($_POST["lab_id"]))
{
    require (dirname(__FILE__)."/../core/connection.php");                                  //Creates Connection to DB
    require (dirname(__FILE__)."/../courses/course_checks.php");                            //Includes required to check if user can delete the lab
    $labID = $_POST["lab_id"];                                                              //Creates variable for POST information

    $labID_to_course_name = mysqli_stmt_init($link);                                        //Init Prepared Statement
    mysqli_stmt_prepare($labID_to_course_name, "SELECT c.courseName FROM courses AS c
                                                JOIN labs AS l ON c.courseID = l.courseRef
                                                WHERE l.labID = ?");                        //Query retrieves name of the course the lab belongs too
    mysqli_stmt_bind_param($labID_to_course_name, "i", $labID );                            //Binds labID to query
    mysqli_stmt_execute($labID_to_course_name);                                             //Executes Prepared Statement
    $course = mysqli_stmt_get_result($labID_to_course_name)->fetch_row();                   //Retrieves result of query

    if(is_lecturer_of_course($link, $course[0]))                                            //Checks if user has access to edit the course
    {
        $delete_lab = mysqli_stmt_init($link);                                              //Init Prepared Statment
        mysqli_stmt_prepare($delete_lab, "DELETE FROM labs WHERE labID = ?");               //Query deletes labs that match the labID
        mysqli_stmt_bind_param($delete_lab, "i", $labID );                                  //Bind labID to query
        mysqli_stmt_execute($delete_lab);                                                   //Execute prepared statement

        echo json_encode(array("success"=> true));                                          //Return success as true
    }
    else                                                                                    //If user does not
    {
        echo json_encode(array("success"=> false));                                         //Return success as false
    }


    mysqli_close($link);                                                                    //Closes DB connection
}