<?php
	include(dirname(__FILE__)."/../core/connection.php");


$accessTypes = mysqli_stmt_init($link);
mysqli_stmt_prepare($accessTypes, "select access_id, access_name from user_access");
mysqli_stmt_execute($accessTypes);


$result = mysqli_stmt_get_result($accessTypes);

while($row = mysqli_fetch_assoc($result)){
	echo "<option value='".$row["access_id"]."'>".$row["access_name"]."</option>";

}

mysqli_close($link);