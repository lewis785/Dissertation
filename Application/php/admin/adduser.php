<?php
include(dirname(__FILE__)."/../core/connection.php");


if (!empty($_POST['firstname']) && !empty($_POST['surname']) && !empty($_POST['access'])) {

	$firstname = mysqli_real_escape_string($link, $_POST['firstname']);
	$surname = mysqli_real_escape_string($link, $_POST['surname']);
	$matric_number = mysqli_real_escape_string($link, $_POST['matric']);
	$accessLevel = mysqli_real_escape_string($link, $_POST['access']);

	echo $firstname. " " . $surname ." ". $matric_number . " ". $accessLevel. "<br>";


	if ( valid_access($link,$accessLevel))
	{
		echo "Access is Valid...<br>";
		if (preg_match("/[a-zA-Z][0-9]{8}/", $matric_number)){
			add_user($link, $firstname,$surname,$matric_number, $accessLevel);
		}
		elseif ($matric_number == "") {
			add_user($link, $firstname,$surname,$matric_number, $accessLevel);
		}
	}


}


function valid_access($link,$access)
{
	echo "Checking Access...<br>";
	$access_exists = mysqli_stmt_init($link);
	mysqli_stmt_prepare($access_exists, "SELECT count(*) FROM user_access WHERE access_id = ?");
	mysqli_stmt_bind_param($access_exists, 'i', $access);
	mysqli_stmt_execute($access_exists);
	$result = mysqli_stmt_get_result($access_exists);
	$count = $result -> fetch_row();

	return ($count[0] == 1);
}



function add_user($link, $first, $surname, $matric, $access)
{
	echo "Adding user... <br>";
	$valid_username = false;
	$password = "test123";

	while(!$valid_username)
	{
		$username = substr($first, 0, 1) . substr($surname, 0,1) . rand(0,9) . rand(0,9) . rand(0,9);
		$username = strtolower($username);

		$is_username_avalible = mysqli_stmt_init($link);
		mysqli_stmt_prepare($is_username_avalible, "SELECT count(*) FROM user_login WHERE username= ?");
		mysqli_stmt_bind_param($is_username_avalible, 's', $username);
		mysqli_stmt_execute($is_username_avalible);
		$result = mysqli_stmt_get_result($is_username_avalible);
		$user_count = $result -> fetch_row();

		$valid_username = $user_count[0] == 0;
		
	}

			echo $username." ".$password." ".$access."<br>";

	mysqli_autocommit($link, FALSE);

	$insertLogin = mysqli_stmt_init($link);
	mysqli_stmt_prepare($insertLogin, "INSERT INTO user_login (username, password, accessLevel) VALUES (?, ?, ?)");
	mysqli_stmt_bind_param($insertLogin, 'ssi', $username, $password, $access);
	
	if (! mysqli_stmt_execute($insertLogin) ){
		mysqli_rollback($link);
		echo "Error Inserting Login Info";
		return;
	}

	$new_id = mysqli_insert_id($link);
	echo $new_id;

	$insertDetailsQuery = 'INSERT INTO user_details (detailsID, firstname, surname, studentID) VALUES (?, ?, ?, ?)';
	$insertDetails = mysqli_stmt_init($link);
	mysqli_stmt_prepare($insertDetails, $insertDetailsQuery);
	mysqli_stmt_bind_param($insertDetails, 'isss',$new_id, $first, $surname, $matric);

	if (! mysqli_stmt_execute($insertDetails) ){
		mysqli_rollback($link);
		echo "Error Inserting Details Info";
		return;
	}

	mysqli_commit($link);



}