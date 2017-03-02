<!DOCTYPE html>
<?php include "../../php/core/alreadyloggedin.php" ?>
<html lang="en">
<head>

    <!--CSS Links -->
    <link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../../css/main.css" rel="stylesheet">

    <!--JS Links -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.js"></script>

    <!--JavaScript -->

    <title>Login Page</title>
</head>

<body>


<div class="col-md-2 col-md-offset-5 ">
    <div class="panel panel-default login-div">
        <div class="panel-heading">
            <h3 class="panel-title">Please sign in</h3>
        </div>
        <div class="panel-body">
            <form accept-charset="UTF-8" role="form"  name="login" method="post" action="home.php">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="Username" name="username" type="text">
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                        </label>
                    </div>
                    <input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
                </fieldset>
            </form>
        </div>
    </div>
</div>


</body>

</html>