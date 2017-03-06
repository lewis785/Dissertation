<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 06/03/2017
 * Time: 14:00
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");

class Security
{

    //Returns the access value of an access name
    public function get_access_value($access_name)
    {
        $con = new ConnectDB();

        $get_access_level = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($get_access_level, "SELECT access_level FROM user_access WHERE access_name= ?");
        mysqli_stmt_bind_param($get_access_level, 's', $access_name);
        mysqli_stmt_execute($get_access_level);
        $result = mysqli_stmt_get_result($get_access_level)->fetch_row();

        mysqli_close($con->link);
        return $result[0];
    }

    //Returns true if user has at least the required access level
    public function has_access_level($access_name)
    {
        $required_access = $this->get_access_value($access_name);           //Gets access value for required accessname
        return ($_SESSION["accesslevel"] >= $required_access);              //Returns True if useraccess is greater or equal to required accesslevel
    }

}