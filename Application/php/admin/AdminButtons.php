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
        return json_encode(array("buttons"=>$output));
    }

    public function manageUsersButtons()
    {

    }


}
//
//$button = new AdminButtons();
//echo ($button->accessDropDown());

