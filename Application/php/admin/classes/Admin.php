<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 08/03/2017
 * Time: 20:04
 */

require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");
require_once(dirname(__FILE__) . "/../../core/classes/Security.php");
require_once(dirname(__FILE__) . "/../../users/classes/UserManager.php");

class Admin extends Security
{

    private $user_manager;

    function __construct()
    {
        $this->user_manager = new UserManager();
    }

    public function addUser($firstname, $surname, $access, $matric = "")
    {
        if ($firstname !== "" && $surname !== "" && $access !== "") {

            $con = new ConnectDB();
            echo $firstname . " " . $surname . " " . $matric . " " . $access . "<br>";


            if ($this->valid_access($con->link, $access)) {
                echo "Access is Valid...<br>";
                if (preg_match("/[a-zA-Z][0-9]{8}/", $matric)) {
                    if (!$this->user_manager->matricExists($con->link, $matric))
                        $this->user_manager->add_user($con->link, $firstname, $surname, $matric, $access);
                } elseif ($matric == "") {
                    $matric = NULL;
                    $this->user_manager->add_user($con->link, $firstname, $surname, $matric, $access);
                }
            }
        }
    }

    public function updateUserAccess($username,$access)
    {
        if($this->hasGreaterAccessThan($access))
        {
            $this->user_manager->updateAccess($username, $access);
        }
    }


    public function getAllUsers()
    {
        $con = new ConnectDB();

        $getAllUsers = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($getAllUsers, "SELECT ua.access_name, ud.firstname, ud.surname, ud.studentID FROM user_details AS  ud
                                              JOIN user_login AS ul ON ud.detailsId = ul.userID
                                              JOIN user_access AS ua ON ul.accessLevel = ua.access_id
                                              WHERE ua.access_level < ? ORDER BY access_level DESC , surname, firstname");
        mysqli_stmt_bind_param($getAllUsers, 'i', $_SESSION['accesslevel']);
        mysqli_stmt_execute($getAllUsers);
        $result = mysqli_stmt_get_result($getAllUsers);

        $outputArray =[];
        while($user = $result->fetch_row())
        {
            array_push($outputArray, $user);
        }

        mysqli_close($con->link);
        return $outputArray;
    }


    private function valid_access($link, $access)
    {
        echo "Checking Access...<br>";
        $access_exists = mysqli_stmt_init($link);
        mysqli_stmt_prepare($access_exists, "SELECT count(*) FROM user_access WHERE access_id = ?");
        mysqli_stmt_bind_param($access_exists, 'i', $access);
        mysqli_stmt_execute($access_exists);
        $result = mysqli_stmt_get_result($access_exists);
        $count = $result->fetch_row();

        return ($count[0] == 1);
    }

    private function generatePassword()
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $len = strlen($characters);
        $rnd_string = "";
        for ($i = 0; $i < 10; $i++)
            $rnd_string .= $characters[rand(0, $len - 1)];

        return $rnd_string;
    }

}
