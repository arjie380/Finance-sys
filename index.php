<?php
include('header-login.php');
include('functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance System - Login</title>
    <link rel="icon" href="finance.png" type="image/png" sizes="32x32">
    <link rel="icon" href="finance-16x16.png" type="image/png" sizes="16x16">
    <link rel="apple-touch-icon" href="apple-icon.png">

    <script src="script.js" defer></script> 
</head>

<body>

    <div class="row vertical-offset-100">
        <div id="response" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
        </div>

        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default login-panel">
                <div class="panel-heading panel-login">
                    <h1 class="text-center">
                    Finance System Login
                    </h1>
                  
                </div>

                <div class="panel-body">
                    <form accept-charset="UTF-8" role="form" method="post" id="login_form">
                        <input type="hidden" name="action" value="login">
                        <fieldset>
                            <div class="input-group form-group">
                                <div class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </div>
                                <input class="form-control required" name="username" id="username" type="text" placeholder="Enter Username">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-addon">
                                    <i class="glyphicon glyphicon-lock"></i>
                                </div>
                                <input class="form-control required" name="password" type="password" placeholder="Enter Password">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                                </label>
                                <!-- Uncomment to enable password reset link -->
                                <!-- <a href="forgot.php" class="float-right">Forgot password?</a> -->
                            </div>
                            <button type="button" id="btn-login" class="btn btn-danger btn-block">Login</button>
                            <br>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php
include('footer.php');
?>
