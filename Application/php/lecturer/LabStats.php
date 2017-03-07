<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 13:16
 */
class LabStats
{


    public function get_lab_stat($link, $course, $lab)
    {
        $stats = [];
    echo $course.$lab;
        array_push($stats, $this->currently_marked_average($link, $course, $lab));
        array_push($stats, $this->lab_average_mark($link, $course, $lab));
        array_push($stats, $this->currently_marked_students_count($link, $lab, $course));
        array_push($stats, $this->students_on_course_count($link, $course));

        return $stats;
    }


    private function lab_average_mark($link, $course, $lab)
    {
        $studentCount = $this->students_on_course_count($link, $course);

        $totalMark = mysqli_stmt_init($link);
        mysqli_stmt_prepare($totalMark, "SELECT sum(mark) FROM lab_answers AS la
                                         JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID
                                          JOIN labs AS l ON lq.labRef = l.labID
                                          JOIN courses AS c ON l.courseRef = c.courseID
                                          WHERE c.courseName = ? AND l.labName = ?");
        mysqli_stmt_bind_param($totalMark, "ss", $course, $lab);
        mysqli_stmt_execute($totalMark);
        $classMark = mysqli_stmt_get_result($totalMark)->fetch_row()[0];

        return number_format(($classMark / $studentCount), 2, ".", "");
    }


    private function currently_marked_average($link, $course, $lab)
    {

        $currentlyMarkedMark = mysqli_stmt_init($link);
        mysqli_stmt_prepare($currentlyMarkedMark, "SELECT avg(totalMark) FROM (
                                      SELECT sum(mark) AS totalMark FROM lab_answers AS la 
                                      JOIN lab_questions AS lq ON la.labQuestionRef = lq.questionID  
                                      JOIN labs AS l ON lq.labRef = l.labID
                                      JOIN courses AS c ON l.courseRef = c.courseID
                                      WHERE c.courseName = ? AND l.labName = ? GROUP BY socRef)inner_query;");
        mysqli_stmt_bind_param($currentlyMarkedMark, "ss", $course, $lab);
        mysqli_stmt_execute($currentlyMarkedMark);
        $markedMark = mysqli_stmt_get_result($currentlyMarkedMark)->fetch_row()[0];

        return number_format(($markedMark), 2, ".", "");
    }

    private function students_on_course_count($link,$course)
    {

        $numberOfStudent = mysqli_stmt_init($link);
        mysqli_stmt_prepare($numberOfStudent, "SELECT count(student) FROM students_on_courses AS soc
                                            JOIN courses AS c ON soc.course = c.courseID
                                             WHERE c.courseName = ?");
        mysqli_stmt_bind_param($numberOfStudent,"s", $course);
        mysqli_stmt_execute($numberOfStudent);
        $result =  mysqli_stmt_get_result($numberOfStudent)->fetch_row()[0];

        return $result;
    }

    private function currently_marked_students_count($link, $lab, $course)
    {
        $studentCount = mysqli_stmt_init($link);
        mysqli_stmt_prepare($studentCount, "SELECT count(distinct(socRef)) FROM lab_answers AS la 
                                        JOIN lab_questions as lq ON la.labQuestionRef = lq.questionID 
                                        JOIN labs as l on lq.labRef = l.labID 
                                        JOIN courses AS c ON l.courseRef = c.courseID 
                                        WHERE c.courseName = ? AND l.labName = ?");
        mysqli_stmt_bind_param($studentCount, "ss", $course, $lab);
        mysqli_stmt_execute($studentCount);
        $numOfStudents = mysqli_stmt_get_result($studentCount)->fetch_row();
        return $numOfStudents[0];
    }


}