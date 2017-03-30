<?php

if (!empty($_POST['firstname']) && !empty($_POST['surname']) && !empty($_POST['access'])) {

    require_once "classes/Admin.php";
    $admin = new Admin();
    if(!empty($_POST['matric']))
        $admin->addUser($_POST["firstname"], $_POST["surname"], $_POST["access"], $_POST["matric"]);
    else
        $admin->addUser($_POST["firstname"], $_POST["surname"], $_POST["access"]);

}