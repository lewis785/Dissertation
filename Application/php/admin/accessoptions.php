<?php
//	include(dirname(__FILE__)."/../core/connection.php");

require_once "classes/AdminButtons.php";

$buttons = new AdminButtons();
echo($buttons->accessDropDown());