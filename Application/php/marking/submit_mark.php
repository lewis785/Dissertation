<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 24/02/2017
 * Time: 13:10
 */


include(dirname(__FILE__)."/../core/connection.php");

if(isset($_POST["mark"]))
{
    print_r( $_POST["mark"]);
}


mysqli_close($link);