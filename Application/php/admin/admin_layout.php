<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 08/03/2017
 * Time: 23:08
 */

require_once "AdminForms.php";

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

        case "manage-students":
            echo($layout->manageStudentButtons());
            break;

        case "manage-lab-helper":
            echo($layout->manageLabHelpers());
            break;

        case "manage-lecturer":
            echo($layout->manageLecturers());
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