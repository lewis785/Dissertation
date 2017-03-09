<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 09/03/2017
 * Time: 18:14
 */

require_once (dirname(__FILE__)."/../core/ConnectDB.php");
require_once (dirname(__FILE__)."/../core/Security.php");

class UserManager extends Security
{

    public function add_user($link, $first, $surname, $matric, $access)
    {
        echo "Adding user... <br>";
        $valid_username = false;
        $password = "test123";

        while (!$valid_username) {
            $username = substr($first, 0, 1) . substr($surname, 0, 1) . rand(0, 9) . rand(0, 9) . rand(0, 9);
            $username = strtolower($username);

            $is_username_avalible = mysqli_stmt_init($link);
            mysqli_stmt_prepare($is_username_avalible, "SELECT count(*) FROM user_login WHERE username= ?");
            mysqli_stmt_bind_param($is_username_avalible, 's', $username);
            mysqli_stmt_execute($is_username_avalible);
            $result = mysqli_stmt_get_result($is_username_avalible);
            $user_count = $result->fetch_row();

            $valid_username = $user_count[0] === 0;

        }

        echo $username . " " . $password . " " . $access . "<br>";

        mysqli_autocommit($link, FALSE);

        $insertLogin = mysqli_stmt_init($link);
        mysqli_stmt_prepare($insertLogin, "INSERT INTO user_login (username, password, accessLevel) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insertLogin, 'ssi', $username, $password, $access);

        if (!mysqli_stmt_execute($insertLogin)) {
            mysqli_rollback($link);
            echo "Error Inserting Login Info";
            return;
        }

        $new_id = mysqli_insert_id($link);
        echo $new_id;

        $insertDetailsQuery = 'INSERT INTO user_details (detailsID, firstname, surname, studentID) VALUES (?, ?, ?, ?)';
        $insertDetails = mysqli_stmt_init($link);
        mysqli_stmt_prepare($insertDetails, $insertDetailsQuery);
        mysqli_stmt_bind_param($insertDetails, 'isss', $new_id, $first, $surname, $matric);

        if (!mysqli_stmt_execute($insertDetails)) {
            mysqli_rollback($link);
            echo "Error Inserting Details Info";
            return;
        }

        mysqli_commit($link);
    }

    public function updateAccess($username, $new_access_level)
    {
        $con = new ConnectDB();
        $accessID = $this->getAccessID($new_access_level);

        $updateAccess = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($updateAccess, "UPDATE user_login  SET accessLevel = ? WHERE username = ? ");
        mysqli_stmt_bind_param($updateAccess, 'is', $accessID, $username);
        mysqli_stmt_execute($updateAccess);
    }



    public function matricExists($link, $matric)
    {
        $matricCheck = mysqli_stmt_init($link);
        mysqli_stmt_prepare($matricCheck, "SELECT count(*) FROM user_details WHERE studentID= ?");
        mysqli_stmt_bind_param($matricCheck, 's', $matric);
        mysqli_stmt_execute($matricCheck);
        $result = mysqli_stmt_get_result($matricCheck);
        $martic_count = $result->fetch_row();

        return $martic_count[0] === 1;
    }


}

$manage = new UserManager();
$manage->updateAccess("Jack", "lab helper");