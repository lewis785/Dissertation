<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 17:59
 */
class IO extends CourseChecks
{
    public function exportLabResults($course, $lab)
    {
        if ($this->hasAccessLevel("lecturer")) {
            if ($this->is_lecturer_of_course($course)) {
                header('Content-Type: application/excel; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen("php://output", "w");
                fputcsv($output, array('username', 'password', 'accessLevel', 'firstname', 'surname', 'matric_number'));
                $query = "SELECT l.username, l.password, l.accessLevel, d.firstname, d.surname, d.studentID FROM user_login AS l  JOIN user_details AS d ON l.userId = d.detailsId ORDER BY userId";
                $result = mysqli_query($link, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    fputcsv($output, $row);
                }
                fclose($output);

            }
        }

    }

    public function export($file_name, $titles, $data)
    {
        ob_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $file_name . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, $titles);
        foreach ($data AS $row) {
            fputcsv($output, $row);
        }
        fclose($output);

    }


}