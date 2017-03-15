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
    <link href="../../css/admin.css" rel="stylesheet">


    <!--JS Links -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.js"></script>
    <script src="../../js/admins.js"></script>
    <script src="../../js/navbar.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script src="../../js/admin/admin_panel.js"></script>
    <script src="../../js/admin/admin_functions.js"></script>


    <!--JavaScripts -->
    <script type="text/javascript">include_navbar("admin");</script>

<!--    <script type="text/javascript">include_sidebar("sidebar_area");</script>-->


    <title>Admin Page</title>
</head>

<body>

<div id="navbar_area"></div>

<div class="container-fluid">
    <div class="row">

        <div class="col-sm-10 col-xs-12 col-sm-offset-1 col-md-8 col-md-offset-2 main">
            <h1 class="col-md-12 page-header"><div class="col-md-4">Admin Control Panel</div></h1>

            <div class="col-md-12" id="admin-panel">

                <button type="button" class="btn btn-info admin-panel-btn col-md-6 col-sm-6 col-xs-12 col-sm-offset-3 col-md-offset-3" onclick="manageUsersButton()">Manage User</button>
                <button type="button" class="btn btn-info admin-panel-btn col-md-6 col-sm-6 col-xs-12 col-sm-offset-3 col-md-offset-3" onclick="manageDatabaseButton()">Manage Database</button>

            </div>




            <div class="col-lg-12">

            </div>

        </div>

    </div>
</div>


</body>
</html>