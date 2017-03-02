<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 02/03/2017
 * Time: 00:40
 */
$managementButtons = "<button class='manage-back-btn btn btn-warning col-md-6 col-md-offset-3' onclick='display_manageable_courses()'>Back</button>";
$managementButtons .= "<button class='manage-btn btn btn-info col-md-4 col-md-offset-1'>Course Stats</button>";
$managementButtons .= "<button class='manage-btn btn btn-info col-md-4 col-md-offset-2'>TEST TEST</button>";
$managementButtons .= "<button class='manage-btn btn btn-info col-md-4 col-md-offset-1' onclick='edit_students()'>Manage Students</button>";
$managementButtons .= "<button class='manage-btn btn btn-info col-md-4 col-md-offset-2'>Manage Lab Helper</button>";



echo json_encode(array("buttons"=>$managementButtons));