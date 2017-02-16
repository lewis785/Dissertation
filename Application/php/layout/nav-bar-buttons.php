
<?php
include(dirname(__FILE__)."/../core/connection.php");
session_start();

$nav_bar_content = '<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li id="profilebtn"><a href="#">Profile</a></li>
                            <li><a href="#">something</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li id="signoutbtn"><a href="../../php/core/signout.php">Signout</a></li>
                        </ul>
                    </li>
                    <li class="active"><a href="#" >Home</a></li>';




if(get_access_value($link,"lecturer") <= $_SESSION["accesslevel"])
{
    $nav_bar_content = '<li><a href="*"> Marking Section </a></li>' . $nav_bar_content;

    $nav_bar_content =    '<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Labs<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Lab Results</a></li>
                                <li><a href="#">List of Labs</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="labmaker.php">Make Lab</a></li>
                                <li><a href="#">Edit Lab</a></li>


                                <li id="signoutbtn"><a href="../../php/core/signout.php">Signout</a></li>
                            </ul>
                        </li>'. $nav_bar_content;

    if(get_access_value($link,"admin") <= $_SESSION["accesslevel"])
    {
    $nav_bar_content = '<li><a href="*"> Admin Section </a></li>' . $nav_bar_content;
    }

}



echo $nav_bar_content;






function get_access_value($link, $access_name){
    $get_access_level = mysqli_stmt_init($link);
    mysqli_stmt_prepare($get_access_level, "SELECT access_id FROM user_access WHERE access_name= ?");
    mysqli_stmt_bind_param($get_access_level, 's', $access_name);
    mysqli_stmt_execute($get_access_level);
    $result = mysqli_stmt_get_result($get_access_level);
    $result = $result -> fetch_row();
    return $result[0];
}



mysqli_close($link);