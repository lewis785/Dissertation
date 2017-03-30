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
            $this->users->add_user($con->link, $firstname, $surname, $matric, 1);
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








//$tot = 0;
//
//for($x=0; $x < $numtogenerate; $x++){
//	$tot += 1;
//	$ran = rand(0,$length-1);
//	if ($ran == 0)
//		$ran++;
//	$first = trim($names[$ran]);
//
//	$ran = rand(0,$length-1);
//	if ($ran == 0)
//		$ran++;
//
//	$surname = trim($names[$ran]);
//	$user = $first.rand(1,9999);
//	$password = generateRandomMixed(10);
//	$cryptpass = crypt($password,"Ba24JDAkfjerio892pp309lE");
//	$occupation = rand(1,7);
//	$dob = rand(1970,2005)."/".rand(1,12)."/".rand(1,28);
//	$email = trim($user)."@".strtolower(generateRandomString(6)).".co.uk";
//	$number = rand(1,100);
//	$postcode = strtoupper(generateRandomString(2)).rand(1,9);
//
//	$ran = rand(0,$length-1);
//	if ($ran == 0)
//		$ran++;
//	$street = $names[$ran]."Street";
//
//	$ran = rand(0,$length-1);
//	if ($ran == 0)
//		$ran++;
//	$city = $names[$ran];
//
//	$date = date('Y-m-d');
//	$access = 1;
//
//
//	// echo "{".$first." ".$surname." ".$user." ".$cryptpass." ".$occupation." ".$dob." ".$email." ".$number." ".$street." ".$city." ".$postcode."} <br>";
//
//
//	$checkusername = mysqli_stmt_init($link);
//	mysqli_stmt_prepare($checkusername, 'Select count(*) from userlogin where UserName= ? ');
//	mysqli_stmt_bind_param($checkusername, 's', $user);
//	mysqli_stmt_execute($checkusername);
//
//	$result = mysqli_stmt_get_result($checkusername);
//	$count = $result -> fetch_row();
//
//	// echo $count[0];
//
//	if ($count[0] == 0) {
//
//		$newlogin = mysqli_stmt_init($link);
//		mysqli_stmt_prepare($newlogin, 'INSERT INTO userlogin (Username, Password, DateJoined, EmailAddress, AccessLevel ) VALUES (?, ?, ?, ?, ?)');
//		mysqli_stmt_bind_param($newlogin, 'ssssi', $user, $cryptpass, $date, $email, $access);
//		$successful = mysqli_stmt_execute($newlogin);
//
//
//		$user_id = mysqli_insert_id($link);
//		if ($successful && $user_id != 0)
//		{
//			$newuserinfo = mysqli_stmt_init($link);
//			mysqli_stmt_prepare($newuserinfo, 'INSERT INTO userdetails (User, FirstName, Surname, DateOfBirth, Occupation) VALUES (?, ?, ?, ?, ?)');
//			mysqli_stmt_bind_param($newuserinfo, 'isssi', $user_id, $first, $surname, $dob, $occupation);
//			$addresssuccessful = mysqli_stmt_execute($newuserinfo);
//
//			$address_id = mysqli_insert_id($link);
//
//			if($addresssuccessful & $address_id != 0)
//			{
//				$newaddress = mysqli_stmt_init($link);
//				mysqli_stmt_prepare($newaddress, 'INSERT INTO useraddress (AddressID, HouseNumberName, StreetName, PostCode , City) VALUES (?, ?, ?, ?, ?)');
//				mysqli_stmt_bind_param($newaddress, 'issss', $address_id, $number, $street, $postcode, $city);
//				$successful = mysqli_stmt_execute($newaddress);
//
//				if($successful)
//				{
//					fwrite($file,"\n(".$first." ".$surname.") {".$user.": ".$password."}");
//					$TotalUCASPoints = 0;
//					$loop = rand(1,15);
//					for($i=0; $i<=$loop; $i++)
//					{
//
//						$course = rand(1,89);
//						$level = rand(1,7);
//						if($level == 1 )
//							$grade = rand(7,12);
//						else
//							$grade = rand(1,6);
//
//						$checkCourse = mysqli_stmt_init($link);
//						mysqli_stmt_prepare($checkCourse, "SELECT count(*) FROM courses WHERE CourseID = ?");
//						mysqli_stmt_bind_param($checkCourse, 'i', $course);
//						mysqli_stmt_execute($checkCourse);
//
//						$result = mysqli_stmt_get_result($checkCourse);
//						$validcourse = $result -> fetch_row();
//
//
//						if($validcourse[0] == 1){
//
//							$checkGradeLevel = mysqli_stmt_init($link);
//							mysqli_stmt_prepare($checkGradeLevel, "SELECT count(*) FROM grades INNER JOIN levels ON grades.GradeSetID = levels.GradeSet
//								WHERE levels.LevelID = ? and grades.GradeID = ?");
//							mysqli_stmt_bind_param($checkGradeLevel, 'ii', $level, $grade);
//							mysqli_stmt_execute($checkGradeLevel);
//
//							$result = mysqli_stmt_get_result($checkGradeLevel);
//							$validgrade = $result -> fetch_row();
//
//
//							if ($validgrade[0] == 1) {
//
//								$getUCASValue = mysqli_stmt_init($link);
//								mysqli_stmt_prepare($getUCASValue, "SELECT count(*), ucaspoints.UCASValue FROM ucaspoints
//									WHERE ucaspoints.Level = ? AND ucaspoints.Grade = ?");
//								mysqli_stmt_bind_param($getUCASValue, 'ii', $level, $grade);
//								mysqli_stmt_execute($getUCASValue);
//								$result = mysqli_stmt_get_result($getUCASValue);
//								$UCAS = $result -> fetch_row();
//
//								if($UCAS[0] == 1)
//									$UCASPoints = $UCAS[1];
//								else
//									$UCASPoints = 0;
//
//
//
//								$insertUserGrade = mysqli_stmt_init($link);
//								mysqli_stmt_prepare($insertUserGrade, 'INSERT INTO userqualifications (User, Course, Level, Grade ) VALUES (?, ?, ?, ?)');
//								mysqli_stmt_bind_param($insertUserGrade, 'iiii', $user_id, $course, $level, $grade);
//								mysqli_stmt_execute($insertUserGrade);
//
//								$TotalUCASPoints += $UCASPoints;
//							}
//
//						}
//					}
//
//					$getPoints = mysqli_stmt_init($link);
//					mysqli_stmt_prepare($getPoints, "SELECT UCASPoints FROM userdetails
//						INNER JOIN userlogin ON userdetails.User = userlogin.UserID
//						where userlogin.UserID = ?");
//					mysqli_stmt_bind_param($getPoints, 'i', $user_id);
//					mysqli_stmt_execute($getPoints);
//					$result = mysqli_stmt_get_result($getPoints);
//					$points = $result -> fetch_row();
//
//					$TotalUCASPoints = $TotalUCASPoints + $points[0];
//
//					$updateUCAS = mysqli_stmt_init($link);
//					mysqli_stmt_prepare($updateUCAS, 'UPDATE userdetails, userlogin SET UCASPoints = ?
//						where userdetails.User = userlogin.UserID AND userlogin.UserID = ?');
//					mysqli_stmt_bind_param($updateUCAS, 'ii', $TotalUCASPoints, $user_id);
//					mysqli_stmt_execute($updateUCAS);
//
//
//				}
//				else
//					deleteuser($file,$link,$user_id,$first,$surname,"Username ".$user." Address Not Valid");
//
//			}
//			else
//				deleteuser($file,$link,$user_id,$first,$surname,"Username ".$user." Details Not Valid");
//		}
//		else
//			deleteuser($file,$link,$user_id,$first,$surname,"Username ".$user." Login Details Invalid");
//	}
//	else
//		deleteuser($file, $link,0,$first,$surname,"Username ".$user." Already Exists");
//}
//
//
//
//
//echo json_encode(array('total'=> $tot));
//
//fclose($file);
//
//
//
//
//function deleteuser($file, $link,$deleteid,$first,$surname,$reason){
//
//	$delete = mysqli_stmt_init($link);
//	mysqli_stmt_prepare($delete, "delete userlogin from userlogin where UserId = ?");
//	mysqli_stmt_bind_param($delete, 'i', $deleteid);
//	mysqli_stmt_execute($delete);
//
//	fwrite($file,"\n(".$first." ".$surname.") !!! Failed to insert: ".$reason." !!!");
//
//}