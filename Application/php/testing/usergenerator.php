<?php

require_once(dirname(__FILE__) . "/../core/classes/ConnectDB.php");
require_once (dirname(__FILE__)."/../users/classes/UserManager.php");


class UserGenerator
{
    private $names;
    private $name_length;
    private $users;


    function __construct()
    {
        $this->names = $this->readInNames();
        $this->name_length = sizeof($this->names);
        $this->users = new UserManager();
    }



    public function createUser($num_of_users)
    {
        $con = new ConnectDB();
        for($i= 0; $i < $num_of_users; $i++) {
            $firstname = $this->createName();
            $surname = $this->createName();
            $matric = $this->generateMatricNumber($con->link);

            echo $firstname . $surname . $matric;
            $this->users->addUser($con->link, $firstname, $surname, $matric, 1);
        }

        mysqli_close($con->link);
    }



    private function readInNames()
    {
        $handle = @fopen("nameslist.txt", "r");
        $array = [];
        while (!feof($handle))
        {
            $buffer = fgets($handle);
            array_push($array, $buffer );
        }
        return $array;
    }


    private function generateMatricNumber($link)
    {
        $avalible = false;
        $checkMatricAvalible = mysqli_stmt_init($link);
        mysqli_stmt_prepare($checkMatricAvalible, "SELECT COUNT(*) FROM user_details WHERE studentID = ?");

        while(!$avalible) {
            $matricString = "H";
            $matricString .= $this->generateNumberString(8);

            mysqli_stmt_bind_param($checkMatricAvalible,"s",$matricString);
            mysqli_stmt_execute($checkMatricAvalible);
            $avalible = mysqli_stmt_get_result($checkMatricAvalible)->fetch_row()[0] === 0;
        }

        return $matricString;
    }

    private function createName()
    {
        return $this->names[rand(0,$this->name_length-1)];
    }


    private function generateRandomString($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generateNumberString($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generatePassword($length) {
        $valid = false;
        $randomString = '';

        while(!$valid){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $uppercase = preg_match('@[A-Z]@', $randomString);
            $lowercase = preg_match('@[a-z]@', $randomString);
            $numbercheck    = preg_match('@[0-9]@', $randomString);

            if($uppercase || $lowercase || $numbercheck || strlen($randomString) >= 8) {
                $valid = true;
            }
        }
        return $randomString;
    }

}


$users = new UserGenerator();
$users->createUser(1);