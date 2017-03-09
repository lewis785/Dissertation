<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 17:59
 */
class IO extends CourseChecks
{
    function exportLabResults($course,$lab){
        if($this->has_access_level("lecturer"))
        {
            if($this->is_lecturer_of_course($course))
            {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen("php://output", "w");
                fputcsv($output, array('username', 'password','accessLevel', 'firstname', 'surname', 'matric_number'));
                $query = "SELECT l.username, l.password, l.accessLevel, d.firstname, d.surname, d.studentID FROM user_login as l  JOIN user_details AS d on l.userId = d.detailsId ORDER BY userId";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_assoc($result))
                {
                    fputcsv($output, $row);
                }
                fclose($output);
            }
        }


    }




}