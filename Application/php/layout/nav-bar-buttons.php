
<?php
require_once(dirname(__FILE__) . "/../core/classes/Security.php");

$secure = new Security();



$nav_bar_content = '<li id="results-nav"><a href="labresults.php">Lab Results</a></li>
                    <li id="signoutbtn"><a href="../../php/core/signout.php">Signout</a></li>';

if($secure->has_access_level("lab helper")) {
    $nav_bar_content = '<li id="marking-nav"><a href="marking.php"> Mark Lab </a></li>' . $nav_bar_content;


    if ($secure->has_access_level("lecturer")) {

        $nav_bar_content = '<li id="labs-nav" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lecturer<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="labresults.php">Lab Results</a></li>
                                <li role="separator" class="divider"></li>
                                <li id="course-nav"><a href="coursemanager.php"> Course Manager </a></li>
                                <li><a href="labmanager.php">Lab Manager</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="labmaker.php">Make Lab</a></li>
                            </ul>
                        </li>' . $nav_bar_content;


        if ($secure->has_access_level("admin")) {
            $nav_bar_content = '<li id="admin-nav"><a href="admin.php"> Admin Section </a></li>' . $nav_bar_content;
        }

    }
}
echo $nav_bar_content;