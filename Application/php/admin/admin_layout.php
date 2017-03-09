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

        case "create-user":
            echo($layout->createUserForm());
            break;

        case "remove-user":
            echo($layout->removeUserForm());
            break;

    }


}