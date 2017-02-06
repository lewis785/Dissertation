<?php
	include(dirname(__FILE__)."/../core/connection.php");

if (!empty($_POST['firstname']) && !empty($_POST['surname'])) {

	$firstname = mysqli_real_escape_string($link, $_POST['firstname']);
	$surname = mysqli_real_escape_string($link, $_POST['surname']);
	$matric_number = mysqli_real_escape_string($link, $_POST['matric']);

    echo $firstname. " " . $surname ." ". $matric_number;

	if (preg_match("/[A-Z][0-9]{8}/", $matric_number)){
		add_user($link, $firstname,$surname,$matric_number);
	}
	elseif ($matric_number == "") {
		add_user($link, $firstname,$surname,$matric_number);
	}

}

function add_user($link, $first, $surname, $matric)
{
	$valid_username = false;
	$password = "test123";

	while(!$valid_username)
	{
		$username = substr($first, 0, 1) . substr($surname, 0,1) . rand(0,9) . rand(0,9) . rand(0,9);
		$username = strtolower($username);
		echo $username;

		$is_username_avalible = mysqli_stmt_init($link);
		mysqli_stmt_prepare($is_username_avalible, "select count(*) from user_login where username= ?");
		mysqli_stmt_bind_param($is_username_avalible, 's', $username);
		mysqli_stmt_execute($is_username_avalible);
		$result = mysqli_stmt_get_result($is_username_avalible);
		$user_count = $result -> fetch_row();

		if ($user_count[0] == 0) {
			$valid_username = true;
		}
	}


	mysqli_autocommit($link, FALSE);

	$insertLoginQuery = 'INSERT INTO user_login (username, password, accessLevel) VALUES (?, ?, ?)';
	$insertLoginDetails = mysqli_stmt_init($link);
	mysqli_stmt_prepare($insertLoginDetails, $insertLoginQuery);
	mysqli_stmt_bind_param($transferstmt, 'ssi', $username, $password, $newmoney);
	if (not mysqli_stmt_execute($transferstmt) ){
	    mysqli_rollback($database);
	    return;
	}

	$insertq = 'UPDATE users SET money=?, bank=? WHERE user_id=' . $userid . ' LIMIT 1';
	$insertstmt = mysqli_stmt_init($database);
	mysqli_stmt_prepare($insertstmt, $insertq);
	mysqli_stmt_bind_param($insertstmt, 'ii', $newmoney, $newbank);

	if (not mysqli_stmt_execute($insertstmt) ){
	    mysqli_rollback($insert);
	    return;
	}

	mysqli_commit($link);



}