<?php
include 'connection.php';
session_start();

if (isset($_SESSION['login_session'])) {

	$verify = mysqli_stmt_init($link);
	mysqli_stmt_prepare($verify, "select count(*), user_login from userlogin where username= ? and password = ?");
	mysqli_stmt_bind_param($verify, 'ss', $_SESSION['login_session']['username'], $_SESSION['login_session']['password']);
	mysqli_stmt_execute($verify);
	$result = mysqli_stmt_get_result($verify);
	$user = $result -> fetch_row();

    $verified = $user[0] == 1;

}
