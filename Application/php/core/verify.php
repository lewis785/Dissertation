<?php

require_once "classes/LoginManager.php";

$access = new LoginManager();
$access->verify();
