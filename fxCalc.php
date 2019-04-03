<!DOCTYPE html>
<?php
 //Check if a session is already set! If it is, then bypass the login page.
if (!isset($_SESSION)) {
    session_start();
}
$username = "";

//Import the LoginDataModel class to our form page.
require_once('LoginDataModel.php');

//Import our FX Data Model class into the form page so that we can refer to it's variables and functions.
require_once('FxDataModel.php');

//Check if the session username is set. If it's not, return to login.php. Important for our security!
if (isset($_SESSION[LoginDataModel::USERNAME])) {
    $username = $_SESSION[LoginDataModel::USERNAME];
    /*
      Is an instance of FxDataModel was in the session.
      If it's not, instantiate one, store it in a local variable for use and then serialize that variable to store in the session.
      If the instance exists in the session, you were supposed to extra it, unserialize it and store it in a local variable for use
      so that it must not be instantiated more than once and do all that work that the constructor did.
     */
    if (!isset($_SESSION[FxDataModel::FX_DM_KEY])) {
        //Initialize the FX Calc Data Model class.
        $fxDataModel = new FxDataModel();
        //Serialize the data model object.
        $_SESSION[FxDataModel::FX_DM_KEY] = serialize($fxDataModel);
    } else {
        $fxDataModel = unserialize($_SESSION[FxDataModel::FX_DM_KEY]);
    }
} else {
    include LoginDataModel::LOGIN_FORM_URL;
    exit;
}



//Initialize a variable to access the associative array of currency codes inside the FXDataModel class.  
//Initialize a variable to access the associative array of the fxCalc.INI file inside of the FXDataModel class.
$fxCurrencies = $fxDataModel->getFxCurrencies();
$iniArray = $fxDataModel->getIniArray();

//Gather user input from dropdown menus and text fields.
$sourceAmount = filter_input(INPUT_POST, $iniArray[$fxDataModel::SOURCE_AMOUNT_KEY], FILTER_VALIDATE_FLOAT); //Ensure that the input is a valid floating point number.
$sourceCurrency = filter_input(INPUT_POST, $iniArray[$fxDataModel::SOURCE_CURRENCY_KEY]);
$destCurrency = filter_input(INPUT_POST, $iniArray[$fxDataModel::DEST_CUREENCY_KEY]);

//Perform the calculation -- EXTRA CREDIT FOR LOOKING UP HOW TO DO THE PROPER DECIMAL FORMATTING??
$destAmount = number_format($fxDataModel->getFxRate($sourceCurrency, $destCurrency) * $sourceAmount, 2);

//Variable to store an error message
$errorMessage = "Please make sure to enter a valid number without the currency symbol (decimals allowed)! Try again!";

//Perform validation and basic error handling.
if (isset($_POST[$fxDataModel::CONVERT_BUTTON])) {
    if (!is_numeric($sourceAmount) || empty($sourceAmount)) { //If the source amount text field is not a numeric value or if it's empty...
        $destAmount = ""; //Set the destination amount field to blank.
        echo "<script type='text/javascript'>alert('$errorMessage');</script>"; //Display the error message with a JS alert.
    }
}
?>

<!--Start of the HTML form!-->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Ryan's F/X Calculator</title>
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <!--Link the CSS to the page.-->
</head>

<body>
    <header>
        <h1>Ryan's Super F/X Calculator</h1>
        <h2>Welcome <?php
                    echo $username;
                    ?></h2>
    </header>
    <br />
    <main>
        <form name="<?php
                    echo $fxDataModel::FORM_NAME_TITLE;
                    ?>" action="<?php
                        echo $fxDataModel::FORM_NAME;
                        ?>" method="post">

            <h3>Choose your source currency code and enter the amount as precise as you want it.</h3>
            <select name="<?php
                            echo $iniArray[$fxDataModel::SOURCE_CURRENCY_KEY];
                            ?>">
                <?php
                foreach ($fxCurrencies as $fxCurrency) {
                    ?>
                <option value="<?php
                                echo $fxCurrency;
                                ?>" <?php
                            if ($fxCurrency === $sourceCurrency) {
                                ?> selected <?php

                                            }
                                            ?>><?php
                                    echo $fxCurrency;
                                    ?></option>
                <?php

            }
            ?>
            </select>

            <input type="text" name="<?php
                                        echo $iniArray[$fxDataModel::SOURCE_AMOUNT_KEY];
                                        ?>" value="<?php
                            if (!is_numeric($sourceAmount)) {
                                echo " ";
                            } else {
                                echo $sourceAmount;
                            }
                            ?>" class="textField" />

            <br />

            <h3>Now choose your destination currency code and click CALCULATE to receive your result!</h3>
            <select name="<?php
                            echo $iniArray[$fxDataModel::DEST_CUREENCY_KEY];
                            ?>">
                <?php
                foreach ($fxCurrencies as $newcurrency) {
                    ?>
                <option value="<?php
                                echo $newcurrency;
                                ?>" <?php
                            if ($newcurrency === $destCurrency) {
                                ?> selected <?php

                                            }
                                            ?>><?php
                                    echo $newcurrency;
                                    ?></option>
                <?php

            }
            ?>
            </select>

            <input type="text" readonly name="<?php
                                                echo $iniArray[$fxDataModel::DEST_AMOUNT_KEY];
                                                ?>" id="outputText" class="textField" value="<?php
                                                                if (isset($_POST[$fxDataModel::CONVERT_BUTTON])) {
                                                                    echo $destAmount;
                                                                }
                                                                ?>" />

            <br /><br />

            <input type="submit" name="<?php
                                        echo $fxDataModel::CONVERT_BUTTON;
                                        ?>" value="CALCULATE" class="button" />
            <input type="reset" name="<?php
                                        echo $fxDataModel::RESET_BUTTON;
                                        ?>" value="RESET" onclick="window.location.href = 'fxCalc.php'" class="button" />

        </form>
    </main>

    <br />

    <footer>
        <p>Copyright (c) 2019 Ryan Lasher. Unauthorized copying of my student work is not the right thing to do, but be inspired by the way I designed my page and come up with your own creative implementation!</p>
    </footer>
</body>

</html> 