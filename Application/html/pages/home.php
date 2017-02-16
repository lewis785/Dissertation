

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
    <script src="../../js/admins.js"></script>
    <script src="../../js/navbar.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script src="../../js/question_adding.js"></script>

    <!--JavaScripts -->
    <script type="text/javascript">include_navbar();</script>
    <script type="text/javascript">include_sidebar("sidebar_area");</script>

    <title>Home Page</title>
</head>

<body>

<div id="navbar_area"></div>

<div class="container-fluid">
    <div class="row">

        <div id="sidebar_area"></div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Webpage Dashboard</h1>
            <span class="glyphicon glyphicon-remove" aria-hidden="true" onclick="$('#question-1').remove()"></span>
            <button onclick="add_scale_question('testing <br>')">Add question</button>






            <div class="col-sm-6 col-sm-offset-3 col-mid-8 col-md-offset-2 tile">
                <div class="col-12">Question Number: <div id="question-number"></div></div>
                <span class="glyphicon glyphicon-remove close-btn" aria-hidden="true" onclick="$('#question-1').remove()"></span>

                <div class="form-group row">
                    <label for="surname-label-input" class="col-md-12 col-md-offset-1 col-form-label">Question</label>
                    <div class="col-md-10 col-md-offset-1">
                        <input class="form-control" type="text" value="" name="surname" id="surname-input">
                    </div>
                </div>

                <div class="form-group col-md-4 col-md-offset-1">
                    <label for="sel1">Select list (select one):</label>
                    <select class="form-control" name="access" id="sel1">
                        <option selected value="no-selection">Select Access Level</option>
                        <?php include "../../php/admin/accessoptions.php"; ?>
                    </select>
                </div>

                <div class="form-group col-md-4 col-md-offset-2">
                    <label for="sel1">Select list (select one):</label>
                    <select class="form-control" name="access" id="sel1">
                        <option selected value="no-selection">Select Access Level</option>
                        <?php include "../../php/admin/accessoptions.php"; ?>
                    </select>
                </div>
            </div>


            <div class="col-lg-12" id="main-text-area">

            </div>
        </div>

    </div>
</div>


</body>
</html>