<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 17/02/2017
 * Time: 17:45
 * @param $link
 * @param $access_name
 * @return
 */

//Returns the access value of an access name
if (!function_exists("get_access_value")) {
    function get_access_value($link, $access_name)
    {
        $get_access_level = mysqli_stmt_init($link);
        mysqli_stmt_prepare($get_access_level, "SELECT access_level FROM user_access WHERE access_name= ?");
        mysqli_stmt_bind_param($get_access_level, 's', $access_name);
        mysqli_stmt_execute($get_access_level);
        $result = mysqli_stmt_get_result($get_access_level)->fetch_row();
        return $result[0];
    }
}

//Returns true if user has at least the required access level
if (!function_exists("has_access_level")) {
    function has_access_level($link, $access_name)
    {
        $required_access = get_access_value($link, $access_name);
        return ($_SESSION["accesslevel"] >= $required_access);
    }
}