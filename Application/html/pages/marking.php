

<?php include "../../php/core/verify.php"; ?>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Admin Terminal">
    <meta name="author" content="Lewis McNeill">

    <!--CSS Links -->
    <link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../../admincss/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="../../css/sidebar.css" rel="stylesheet">
    <link href="../../css/main.css" rel="stylesheet">

    <!--JS Links -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.js"></script>
    <script src="../../js/navbar.js"></script>
    <script src="../../js/labs/display_questions.js"></script>
    <script src="../../js/marking.js"></script>



    <!--JavaScripts -->
    <script type="text/javascript">include_navbar();</script>


    <title>Home Page</title>
</head>

<body>

<div id="navbar_area"></div>

<div class="container-fluid">
    <div class="row">

        <div id="sidebar_area"></div>

        <div class="col-sm-8 col-md-8 col-md-offset-2 col-sm-offset-2 main">
            <h1 class="page-header">Webpage Dashboard</h1>
            <div class="col-lg-12" id="main-text-area">
                <button onclick="load_lab('Lab 1', 'Data Management')">Load Lab</button>
            </div>

            <div class="col-md-12" id="question-area">
                <?php include "../../php/courses/get_courses.php"; courses_button(); ?>


            </div>
        </div>

    </div>
</div>


<footer class="panel-footer fix-bottom" id="marking-submit-bar">
    <button class="btn btn-default col-md-2 col-md-offset-2" id="back-btn">Back</button>
    <button class="btn btn-success col-md-2 col-md-offset-1">Submit</button>
</footer>


</body>
</html>