<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 09/03/2017
 * Time: 13:57
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once(dirname(__FILE__) . "/../../courses/classes/Courses.php");
require_once(dirname(__FILE__) . "/../../students/classes/Student.php");

class LabHelper extends Security
{

    private $Students;
    private $Courses;

    function __construct()
    {
        $this->Students = new Student();
        $this->Courses = new Courses();
    }

    public function getAllLabHelpers($filter = "")
    {
        $con = new ConnectDB();

        $allLabHelpers = mysqli_stmt_init($con->link);


        if($filter === "") {
            mysqli_stmt_prepare($allLabHelpers, "SELECT ud.firstname, ud.surname, ud.studentID FROM user_details AS  ud
                                              JOIN user_login AS ul ON ud.detailsId = ul.userID
                                              JOIN user_access AS ua ON ul.accessLevel = ua.access_id
                                              WHERE ua.access_name = 'lab helper'");
        }
        else{
            $filter = '%'.$filter."%";
            mysqli_stmt_prepare($allLabHelpers, "SELECT ud.firstname, ud.surname, ud.studentID FROM user_details AS  ud
                                              JOIN user_login AS ul ON ud.detailsId = ul.userID
                                              JOIN user_access AS ua ON ul.accessLevel = ua.access_id
                                              WHERE ua.access_name = 'lab helper' AND (CONCAT(ud.firstname, ' ', ud.surname)
                                              LIKE  ? OR ud.studentID LIKE ?) ");
            mysqli_stmt_bind_param($allLabHelpers,"ss",$filter, $filter);
        }

        mysqli_stmt_execute($allLabHelpers);
        $result = mysqli_stmt_get_result($allLabHelpers);

        $output_array = [];

        while($helper = $result->fetch_row())
            array_push($output_array, $helper);

        return $output_array;
        
    }
    
    public function getCourseLabHelpers($course)
    {
        $con = new ConnectDB();

        $courseHelpers = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($courseHelpers, "Select ud.firstname, ud.surname, ud.studentID FROM user_details AS  ud
                                              JOIN lab_helpers AS lh ON ud.detailsId = lh.userRef
                                              JOIN courses as c ON lh.course = c.courseID
                                              WHERE c.courseName = ?");
        mysqli_stmt_bind_param($courseHelpers, "s", $course);
        mysqli_stmt_execute($courseHelpers);
        $result = mysqli_stmt_get_result($courseHelpers);

        $output_array = [];

        while($helper = $result->fetch_row())
            array_push($output_array, $helper);

        return $output_array;

    }

    public function getNonCourseLabHelpers($course, $filter = "")
    {
        $lab_helpers = $this->getAllLabHelpers($filter);
        $course_helpers = $this->getCourseLabHelpers($course);

        foreach($lab_helpers as $key => $helper){
            foreach($course_helpers as $c_helper){
                if($helper[2] == $c_helper[2])
                {
                    unset($lab_helpers[$key]);
                    break;
                }
            }
        }

        return $lab_helpers;
    }


    public function removeLabHelper($course,$student)
    {
        $con = new ConnectDB();
        $success = false;

        if($this->Courses->has_access_level("lecturer")) {
            $removeLabHelper = mysqli_stmt_init($con->link);
            mysqli_stmt_prepare($removeLabHelper, "DELETE FROM lab_helpers WHERE labHelperID IN 
                                                  (SELECT lh.labHelperID FROM (SELECT * FROM lab_helpers) AS lh 
                                                  JOIN user_details AS ud ON lh.userRef = ud.detailsId 
                                                  JOIN courses AS c ON lh.course = c.courseID 
                                                  WHERE c.courseName = ? AND ud.studentID = ?)");
            mysqli_stmt_bind_param($removeLabHelper, "ss", $course, $student);
            $success = mysqli_stmt_execute($removeLabHelper);
        }

        mysqli_close($con->link);
        return json_encode(array("success"=>$success));

    }

    public function addLabHelper($course, $student)
    {
        $con = new ConnectDB();
        $success = false;

        if($this->Courses->has_access_level("lecturer"))
        {
            $studentID= $this->Students->studentIDFromMatric($student);
            $courseID = $this->Courses->get_course_id($course);

            if(is_int($studentID) && is_int($courseID) && $this->isLabHelper($con->link, $student) && !$this->alreadyLabHelperOf($con->link, $course, $student))
            {

                $insertLabHelper = mysqli_stmt_init($con->link);
                mysqli_stmt_prepare($insertLabHelper, "INSERT INTO lab_helpers (course, userRef) VALUES (?, ?)");
                mysqli_stmt_bind_param($insertLabHelper, "ss", $courseID, $studentID);
                $success = mysqli_stmt_execute($insertLabHelper);
            }
        }

        mysqli_close($con->link);
        return json_encode(array("success"=>$success));
    }

    private function isLabHelper($link, $student)
    {
        $checkLabHelper = mysqli_stmt_init($link);
        mysqli_stmt_prepare($checkLabHelper, "SELECT count(*) FROM user_details AS ud 
                                                JOIN user_login AS ul ON ud.detailsId = ul.userID
                                                JOIN user_access AS ua ON ul.accessLevel = ua.access_id
                                                WHERE ua.access_name = 'lab helper' AND ud.studentID = ? ");
        mysqli_stmt_bind_param($checkLabHelper, "s", $student);
        mysqli_stmt_execute($checkLabHelper);
        $result = mysqli_stmt_get_result($checkLabHelper)->fetch_row()[0];

        return $result === 1;
    }

    private function alreadyLabHelperOf($link, $course, $student)
    {
        $alreadyHelper = mysqli_stmt_init($link);
        mysqli_stmt_prepare($alreadyHelper, "SELECT count(*) FROM lab_helpers AS lh 
                                                JOIN user_details AS ud ON ud.detailsId = lh.userRef
                                                JOIN courses AS c ON lh.course = c.courseID
                                                WHERE c.courseName = ? AND ud.studentID = ? ");
        mysqli_stmt_bind_param($alreadyHelper, "ss", $course,  $student);
        mysqli_stmt_execute($alreadyHelper);
        $result = mysqli_stmt_get_result($alreadyHelper)->fetch_row()[0];

        return $result === 1;
    }

}

//$helper = new LabHelper();
//print_r($helper->addLabHelper("Software Development 1", "H00100234"));