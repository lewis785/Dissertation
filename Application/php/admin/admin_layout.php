<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 08/03/2017
 * Time: 23:08
 */

require_once "classes/AdminForms.php";

if(isset($_POST["buttonType"]))
{

    $layout = new AdminForms();

    $selected = $_POST["buttonType"];

    switch ($selected)
    {
        case "main-panel":
            echo($layout->panelButtons());
            break;

        case "user-manager":
            echo($layout->manageUsersButtons());
            break;

        case "db-manager":
            echo($layout->manageDatabaseButton());
            break;

        case "manage-students":
            echo($layout->manageStudents());
            break;

        case "student-courses-table":
            echo($layout->studentCoursesTable($_POST["course"]));
            break;

        case "manage-lab-helper":
            echo($layout->manageLabHelpers());
            break;

        case "lab-helper-tables":
            echo($layout->labHelpersTables($_POST["course"]));
            break;

        case "manage-lecturer":
            echo($layout->manageLecturers());
            break;

        case "lecturer-tables":
            echo($layout->lecturerTable($_POST["course"]));
            break;



        case "create-user":
            echo($layout->createUserForm());
            break;

        case "remove-user":
            echo($layout->removeUserForm());
            break;

        case "update-user":
            echo($layout->updateUserForm());
            break;


        case "add-to-course":
            echo($layout->manageStudents());
            break;

    }


}