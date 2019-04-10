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
    <link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <header>
        <h1>Login to Ryan's Super F/X Calculator</h1>
    </header>
    <form name="login" action="login.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="<?php echo $loginArray[LoginDataModel::USERNAME_KEY]; ?>">

        <br />

        <label for="password">Password</label>
        <input type="password" name="<?php echo $loginArray[LoginDataModel::PASSWORD_KEY]; ?>">

        <br />

        <div class="buttons">
            <input type="submit" value="LOGIN" name="login" id="loginButton">
            <input type="reset" value="RESET" name="reset" onclick="window.location.href = 'login.php'" id="resetButton">
        </div>


    </form>

    <footer>
        <p>Copyright (c) 2019 Ryan Lasher. Unauthorized copying of my student work is not the right thing to do, but be inspired by the way I designed my page and come up with your own creative implementation!</p>
    </footer>
</body>

</html>