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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="../../bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="../../js/navbar.js"></script>
    <script type="text/javascript" src="../../js/sidebar.js"></script>



    <!--JavaScripts Functions -->
    <script type="text/javascript">include_navbar("results");</script>


    <title>Lab Results Page</title>
</head>

<body>

<div id="navbar_area"></div>

<div class="container-fluid">
    <div class="row">

        <div id="sidebar_area"></div>

        <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 main">
            <h1 class="page-header">Lab Results</h1>
            <div class="col-lg-12" id="main-text-area">
                <?php include"../../php/students/display_lab_marks.php"?>
            </div>
        </div>

    </div>
</div>


</body>
</html>