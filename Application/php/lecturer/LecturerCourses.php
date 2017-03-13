<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 07/03/2017
 * Time: 17:05
 */
class LecturerCourses
{

    public function displayCourseManager()
    {
        $managementButtons = "<button class='manage-back-btn btn btn-warning btn-text-wrap col-md-6 col-sm-12 col-xs-12 col-md-offset-3' onclick='display_manageable_courses()'>Back</button>";
        $managementButtons .= "<button class='manage-btn btn btn-info btn-text-wrap col-md-4 col-sm-12 col-xs-12 col col-md-offset-1'>Course Stats</button>";
        $managementButtons .= "<button class='manage-btn btn btn-info btn-text-wrap col-md-4 col-sm-12 col-xs-12 col-md-offset-2'>TEST TEST</button>";
        $managementButtons .= "<button class='manage-btn btn btn-info btn-text-wrap col-md-4 col-sm-12 col-xs-12 col-md-offset-1' onclick='edit_students()'>Manage Students</button>";
        $managementButtons .= "<button class='manage-btn btn btn-info btn-text-wrap col-md-4 col-sm-12 col-xs-12 col-md-offset-2'>Manage Lab Helper</button>";

        return json_encode(array("buttons"=>$managementButtons));
    }


}