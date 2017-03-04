
<?php
include(dirname(__FILE__)."/../core/connection.php");
include(dirname(__FILE__)."/../core/check_access_level.php");

$nav_bar_content = '<li id="results-nav"><a href="labresults.php">Lab Results</a></li>
                    <li id="home-nav"><a href="home.php" >Home</a></li>
                    <li id="signoutbtn"><a href="../../php/core/signout.php">Signout</a></li>';

if(get_access_value($link, "lab helper") <= $_SESSION["accesslevel"]) {
    $nav_bar_content = '<li id="marking-nav"><a href="marking.php"> Mark Lab </a></li>' . $nav_bar_content;

    if (get_access_value($link, "lecturer") <= $_SESSION["accesslevel"]) {


        $nav_bar_content = '<li id="labs-nav" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Labs<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="labresults.php">Lab Results</a></li>
                                <li><a href="labmanager.php">List of Labs</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="labmaker.php">Make Lab</a></li>
                                <li><a href="#">Edit Lab</a></li>
                            </ul>
                        </li>' . $nav_bar_content;

        $nav_bar_content = '<li id="course-nav"><a href="coursemanager.php"> Course Manager </a></li>' . $nav_bar_content;

        if (get_access_value($link, "admin") <= $_SESSION["accesslevel"]) {
            $nav_bar_content = '<li id="admin-nav"><a href="admin.php"> Admin Section </a></li>' . $nav_bar_content;
        }

    }
}
echo $nav_bar_content;
mysqli_close($link);