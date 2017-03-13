<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 08/03/2017
 * Time: 20:06
 */
require_once "Admin.php";

class AdminButtons extends Admin
{

    public function accessDropDown()
    {
        $con = new ConnectDB();

        $accessTypes = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($accessTypes, "SELECT access_id, access_name FROM user_access WHERE access_level < ? ORDER BY access_level");
        mysqli_stmt_bind_param($accessTypes, "s", $_SESSION["accesslevel"]);
        mysqli_stmt_execute($accessTypes);

        $result = mysqli_stmt_get_result($accessTypes);

        $output = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($output, [ $row["access_id"], $row["access_name"] ]);
        }

        mysqli_close($con->link);
        return $output;
    }

    
    public function panelButtons()
    {
        $buttons = $this->buttonLayout( "Manage User", "manageUsersButton()");
        $buttons .= $this->buttonLayout( "Manage Database", "manageUsersButton()");

        return json_encode(array("layout"=>$buttons));
    }



    public function manageUsersButtons()
    {

        $buttons = $this->buttonLayout("Back", "mainPanel()", "warning");
        $buttons.= $this->buttonLayout("Add Users", "addUserForm()");
        $buttons.= $this->buttonLayout("Remove Users", "removeUserForm()");
        $buttons.= $this->buttonLayout("Update User Access", "updateUserForm()");
        $buttons.= $this->buttonLayout("Manage Students", "manageStudentButtons()");
        $buttons.= $this->buttonLayout("Manage Lab Helpers", "manageLabHelpers()");
        $buttons.= $this->buttonLayout("Manage Lecturers", "manageLecturers()");

        return json_encode(array("layout"=>$buttons));
    }


    public function buttonLayout($text, $action, $type="info")
    {
        return "<button type='button' class='btn btn-$type admin-panel-btn col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-md-offset-3' onclick='$action'>$text</button>";
    }

}
//
//$button = new AdminButtons();
//echo ($button->accessDropDown());

