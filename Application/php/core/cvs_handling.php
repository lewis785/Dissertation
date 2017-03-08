<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 15/02/2017
 * Time: 19:07
 */


include 'connection.php';



if(isset($_POST["Import"])){
    $filename=$_FILES["file"]["tmp_name"];
    if($_FILES["file"]["size"] > 0)
    {
        $file = fopen($filename, "r");
        mysqli_autocommit($link, FALSE);
        $first_row = true;
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
        {
            if(!$first_row) {
                mysqli_autocommit($link, FALSE);

                echo $getData[0] . $getData[1] . $getData[2] . $getData[3] . $getData[4] . $getData[5];
                $insertLogin = mysqli_stmt_init($link);
                mysqli_stmt_prepare($insertLogin, "INSERT INTO user_login (username, password, accessLevel) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($insertLogin, 'ssi', $getData[0], $getData[1], $getData[2]);

                if (!mysqli_stmt_execute($insertLogin)) {
                    mysqli_rollback($link);
                    echo "Error Inserting Login Info";
                    return;
                }

                $new_id = mysqli_insert_id($link);
                $insertDetailsQuery = 'INSERT INTO user_details (detailsID, firstname, surname, studentID) VALUES (?, ?, ?, ?)';
                $insertDetails = mysqli_stmt_init($link);
                mysqli_stmt_prepare($insertDetails, $insertDetailsQuery);
                mysqli_stmt_bind_param($insertDetails, 'isss', $new_id, $getData[3], $getData[4], $getData[5]);

                if (!mysqli_stmt_execute($insertDetails)) {
                    mysqli_rollback($link);
                    echo "Error Inserting Details Info";
                    return;
                }

                mysqli_commit($link);
            }
            $first_row = false;
        }
        fclose($file);
    }
}



if(isset($_POST["Export"])){

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, array('username', 'password','accessLevel', 'firstname', 'surname', 'matric_number'));
    $query = "SELECT l.username, l.password, ua.access_name, d.firstname, d.surname, d.studentID FROM user_login as l  
              JOIN user_details AS d on l.userId = d.detailsId 
              JOIN user_access AS ua on l.accessLevel = ua.access_id 
              ORDER BY userId";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_assoc($result))
    {
        fputcsv($output, $row);
    }
    fclose($output);
}
