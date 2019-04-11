<?php
//Include the LoginDataModel and FxDataModel classes so they can be called later.
include_once 'LoginDataModel.php';
include_once 'FxDataModel.php';

//Start a new session if there is no existing session found.
if (!isset($_SESSION)) {
    session_start();
}

//If there is no username in the session variable, include the login page.
if (!isset($_SESSION[LoginDataModel::USERNAME_SESSION_KEY])) {
    include_once LoginDataModel::LOGIN_PHP_FILENAME;
}

//New instance of LoginDataModel.
$loginDataModel = new LoginDataModel();

//Return and store the login array (login.ini file).
$loginArray = $loginDataModel->getLoginArray();

//If the username exists in the array and is set in the session, and the validateUser method returns true...
if (
    array_key_exists($loginArray[LoginDataModel::USERNAME_KEY], $_POST) &&
    isset($loginArray[LoginDataModel::USERNAME_KEY]) &&
    $loginDataModel->validateUser(
        $_POST[$loginArray[LoginDataModel::USERNAME_KEY]],
        $_POST[$loginArray[LoginDataModel::PASSWORD_KEY]]
    )
) {

    //Set the username session key to be equal to the value of the supplied (and now validated) username.
    $_SESSION[LoginDataModel::USERNAME_SESSION_KEY] = $_POST[$loginArray[LoginDataModel::USERNAME_KEY]];

    //After sucessful authentication ,include the fxCalc.php page and exit the login process.
    include(FxDataModel::FX_PHP_FILENAME);
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Ryan's Super F/X Calculator</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- I am using Bootstrap 4 to make styling easier. I may still use some custom CSS to override some styles if I want. -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Custom CSS -->
    <link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-light">
    <div class="container center">
        <header>
            <h1 class="display-1 text-primary">Login to Ryan's Super F/X Calculator</h1>
        </header>
        <div class="row">
            <form name="login" action="login.php" method="post" class="was-validated">
                <div class="form-group col-md">
                    <label for="username">Username</label>
                    <input type="text" placeholder="Enter username." name="<?php echo $loginArray[LoginDataModel::USERNAME_KEY]; ?>" class="form-control mb-2" id="username" required>
                </div>
                <div class="form-group col-md">
                    <label for="password">Password</label>
                    <input type="password" placeholder="Enter password." name="<?php echo $loginArray[LoginDataModel::PASSWORD_KEY]; ?>" class="form-control mb-2" id="password" required>
                </div>
                <div class=" btn-group form-group col-md">
                    <input type="submit" value="LOGIN" name="login" id="loginButton" class="btn btn-outline-primary mb-2">
                    <input type="reset" value="RESET" name="reset" onclick="window.location.href = 'login.php'" id="resetButton" class="btn btn-outline-warning mb-2">
                </div>
        </div>

        </form>

        <footer class="font-italic text-secondary">
            <p>Copyright (c) 2019 Ryan Lasher. Unauthorized copying of my student work is not the right thing to do, but be inspired by the way I designed my page and come up with your own creative implementation!</p>
        </footer>
    </div>

</body>

</html>