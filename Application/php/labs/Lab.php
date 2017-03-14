<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 17:54
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../courses/CourseChecks.php");



class Lab extends CourseChecks
{

    public function get_students($course, $filter="")
    {
        $output = [];
        if ($this->can_mark_course($course)) {
            $con = new ConnectDB();

            $get_students = mysqli_stmt_init($con->link);
            if($filter == "") {
                mysqli_stmt_prepare($get_students, "SELECT d.firstname, d.surname, d.studentID FROM students_on_courses AS soc 
                                        JOIN user_details AS d ON soc.student = d.detailsId 
                                        JOIN courses AS c ON soc.course = c.courseID 
                                        WHERE c.courseName = ? 
                                        ORDER BY d.surname, d.firstname");
                mysqli_stmt_bind_param($get_students, 's', $course);
            }
            else{
                $filter = "%$filter%";
                mysqli_stmt_prepare($get_students, "SELECT d.firstname, d.surname, d.studentID FROM students_on_courses AS soc 
                                        JOIN user_details AS d ON soc.student = d.detailsId 
                                        JOIN courses AS c ON soc.course = c.courseID 
                                        WHERE c.courseName = ? AND (CONCAT(d.firstname,' ',d.surname) LIKE ?)
                                        ORDER BY d.surname, d.firstname");
                mysqli_stmt_bind_param($get_students, 'ss', $course, $filter);
            }
            mysqli_stmt_execute($get_students);


            $output = mysqli_stmt_get_result($get_students);
            mysqli_close($con->link);
        }
        return $output;
    }

    public function studentsFromLabID($labID)
    {
        $con = new ConnectDB();

        $students  = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($students, "SELECT ud.studentID  FROM user_details AS ud
                                          JOIN students_on_courses AS soc ON ud.detailsId = soc.student
                                          JOIN courses AS c ON soc.course = c.courseID
                                          JOIN labs AS l ON c.courseID = l.courseRef
                                          WHERE l.labID = ?");
        mysqli_stmt_bind_param($students, "s", $labID);
        mysqli_stmt_execute($students);
        $results = mysqli_stmt_get_result($students);

        $students_array = [];
        while($student = $results->fetch_row())
            array_push($students_array, $student[0]);

        mysqli_close($con->link);
        return $students_array;
    }



    public function getLabs($course)
    {
        $con = new ConnectDB();
        $labsArray = [];

        $get_labs = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_labs, "SELECT l.labName, l.canMark FROM labs AS l 
                                        JOIN courses AS c ON l.courseRef = c.courseID 
                                        WHERE c.courseName = ? ORDER BY l.labID");
        mysqli_stmt_bind_param($get_labs, 's', $course);
        mysqli_stmt_execute($get_labs);
        $result = mysqli_stmt_get_result($get_labs);

        while($lab = $result->fetch_row()) {
            array_push($labsArray, $lab);
        }

        mysqli_close($con->link);
        return $labsArray;
    }




    public function lab_total_mark($courseName, $labName)
    {
        $con = new ConnectDB();

        $labTotalMark = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($labTotalMark, "SELECT SUM(maxMark) FROM lab_questions as lq 
                                        JOIN labs AS l ON lq.labRef = l.labID
                                        JOIN courses AS c ON l.courseRef = c.courseID
                                        WHERE c.courseName = ? AND l.labName = ? ");
        mysqli_stmt_bind_param($labTotalMark, "ss", $courseName, $labName);

        if(mysqli_stmt_execute($labTotalMark))
        {
            $result = mysqli_stmt_get_result($labTotalMark)->fetch_row();
            mysqli_close($con->link);
            return ($result[0]);
        }
        mysqli_close($con->link);
        return -1;
    }

    public function get_lab_id($course, $lab)
    {
        $con = new ConnectDB();

        $getLabIDQuery = 'SELECT labID FROM labs AS l
                      JOIN courses AS c ON l.courseRef = c.courseID
                      WHERE c.courseName = ? AND l.labName = ?';                //Query gets lab ID for course name and lab name
        $getLabID = mysqli_stmt_init($con->link);                                    //Init Prepared Statement
        mysqli_stmt_prepare($getLabID, $getLabIDQuery);
        mysqli_stmt_bind_param($getLabID, 'is',$course, $lab);                  //Bind course and lab variables
        mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
        $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result

        mysqli_close($con->link);
        return $result[0];                                                      //Return first item in result array
    }

    public function getLabName($labID)
    {
        $con = new ConnectDB();

        $getLabIDQuery = 'SELECT l.labName FROM labs AS l WHERE l.labID = ?';                //Query gets lab name from Id
        $getLabID = mysqli_stmt_init($con->link);                                    //Init Prepared Statement
        mysqli_stmt_prepare($getLabID, $getLabIDQuery);
        mysqli_stmt_bind_param($getLabID, 'i',$labID);                  //Bind course and lab variables
        mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
        $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result

        mysqli_close($con->link);
        return $result[0];
    }

    public function courseFromLabID($labID)
    {
        $con = new ConnectDB();

        $getLabIDQuery = 'SELECT c.courseName FROM labs AS l 
                          JOIN courses AS c ON l.courseRef = c.courseID
                          WHERE l.labID = ?';                                   //Query gets course name from Id
        $getLabID = mysqli_stmt_init($con->link);                               //Init Prepared Statement
        mysqli_stmt_prepare($getLabID, $getLabIDQuery);
        mysqli_stmt_bind_param($getLabID, 'i',$labID);                           //Bind course and lab variables
        mysqli_stmt_execute($getLabID);                                         //Execute Prepared Statement
        $result = mysqli_stmt_get_result($getLabID)->fetch_row();               //Get Result

        mysqli_close($con->link);
        return $result[0];

    }


    public function get_questionID($labID, $questionNum)
    {
        $con = new ConnectDB();

        $get_questionID = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_questionID, "SELECT questionID FROM lab_questions WHERE labRef = ? AND questionNumber = ?");
        mysqli_stmt_bind_param($get_questionID, 'ii', $labID, $questionNum);
        mysqli_stmt_execute($get_questionID);
        $result = mysqli_stmt_get_result($get_questionID)->fetch_row();

        mysqli_close($con->link);
        return $result[0];
    }

}

//$lab = new Lab();
//print_r($lab->get_students("Software Development 1", "ciaran")->fetch_row());