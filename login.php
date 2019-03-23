<!DOCTYPE html>
<?php
//Import the LoginDataModel class.
require_once('LoginDataModel.php');

//Initialize an object of the LoginDataModel class.
//Initialize a variable to fetch the associative array of the fxUsers.ini file from the LoginDataModel class.
//Initialize a variable to fetch the associative array of the login.ini file from the LoginDataModel class.
$fxLogin = new LoginDataModel();
//$userArray = $fxLogin->getUserArray();
$loginArray = $fxLogin->getLoginArray();

//Set username and password to empty strings
$username = "";
$password = "";

//Validation -- check that the username exists in the fxUsers.ini file and make sure the associated password matches the INI.
if (array_key_exists($loginArray[$fxLogin::USERNAME], $_POST)) {
    $username = filter_input(INPUT_POST, $loginArray[$fxLogin::USERNAME]);
    $password = filter_input(INPUT_POST, $loginArray[$fxLogin::PASSWORD]);

    //Perform error handling.
    if ($fxLogin->validateUser($username, $password) == true) {//If the validation passes...
        //Load fxCalc.php when valid login is submitted.
        include $fxLogin::FX_CALC_FORM_URL;
        exit(); //Stop processing the login stuff!
    } else {
        echo "<script type='text/javascript'>alert('Access denied! You entered an invalid username and password...or one or both of the fields are empty!')</script>";
    }
}
?>

<!--Start of the HTML form!-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Login to Ryan's F/X Calculator</title>
        <link href="css/main.css" rel="stylesheet" type="text/css"/> <!--Link the CSS to the page.-->
    </head>
    <body>
        <header>
            <h1>Login to Ryan's Super F/X Calculator</h1>
        </header>
        <br/>
        <main>
            <form name="<?php echo $loginArray[$fxLogin::LOGIN_FORM_NAME] ?>" action="<?php echo $fxLogin::LOGIN_FORM_URL ?>" method="post">
                <label>Username: </label><input name="<?php echo $loginArray[$fxLogin::USERNAME] ?>" value="<?php
                if (isset($_POST[$fxLogin::LOGIN_BUTTON])) {
                    echo $username;
                }
                ?>" type="text" /><br /><br />
                <label>Password: </label><input name="<?php echo $loginArray[$fxLogin::PASSWORD] ?>" value="<?php echo $password ?>" type="password" /><br /><br />
                <input type="submit" value="LOGIN" name="<?php echo $fxLogin::LOGIN_BUTTON; ?>" class="button" id="loginButton"/>
                <input type="reset" value="RESET" name="<?php echo $fxLogin::RESET_BUTTON; ?>" onclick="window.location.href = 'login.php'" class="button" id="resetButton"/>
            </form>
        </main>
        <footer>
            <p>Copyright (c) 2019 Ryan Lasher. Unauthorized copying of my student work is not the right thing to do, but be inspired by the way I designed my page and come up with your own creative implementation!</p>
        </footer>
    </body>
</html>