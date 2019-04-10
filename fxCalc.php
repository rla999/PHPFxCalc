<?php
//Import the FxDataModel and LoginDataModel classes.
require_once('FxDataModel.php');
require_once('LoginDataModel.php');

//If the session isn't already started...then start it.
if (!isset($_SESSION)) {
    session_start();
}

//If there is no username in the session variable, include the login page.
if (!isset($_SESSION[LoginDataModel::USERNAME_SESSION_KEY])) {
    require_once LoginDataModel::LOGIN_PHP_FILENAME;
    exit;
}

//If there the FxDataModel is not already in the session, then create a new instance of the FxDataModel class and serialize the object. Otherwise, unserialize the object.
if (!isset($_SESSION[FxDataModel::FX_SESSION_KEY])) {
    $fxDataModel = new FxDataModel();
    $_SESSION[FxDataModel::FX_SESSION_KEY] = serialize($fxDataModel);
} else {
    $fxDataModel = unserialize($_SESSION[FxDataModel::FX_SESSION_KEY]);
}

//Retrieves the arrays for the currency codes and the INI files respectively.
$fxCurrencies = $fxDataModel->getFxCurrencies();
$fxIniArray = $fxDataModel->getIniArray();

//Set the text fields for source amount and destination amount to blank.
$srcAmnt = '';
$dstAmnt = '';


//If the key for the source currency, destination currency, or the destination amount already exists in POST...
if (array_key_exists($fxIniArray[FxDataModel::DST_AMT_KEY], $_POST) &&
        array_key_exists($fxIniArray[FxDataModel::DST_CUCY_KEY], $_POST) &&
        array_key_exists($fxIniArray[FxDataModel::SRC_CUCY_KEY], $_POST)) {

    // Returns the index of the currency codes array with it's corresponding value.
    $srcCucy = array_search($_POST[$fxIniArray[FxDataModel::SRC_CUCY_KEY]], $fxCurrencies);
    $dstCucy = array_search($_POST[$fxIniArray[FxDataModel::DST_CUCY_KEY]], $fxCurrencies);

    //Set the source amount to whatever the user enters in the form.
    $srcAmnt = $_POST[$fxIniArray[FxDataModel::SRC_AMT_KEY]];
    //Populate the desintation amount field by calculating the F/X rate using the getOutput function in the FxDataModel class.
    $dstAmnt = $fxDataModel->getOutput($srcAmnt, $srcCucy, $dstCucy);

    //Variable to check whether the source amount entered by the user is a valid floating point number.
    $isNumeric = is_numeric($srcAmnt);

    //If the source amount fails the numeric test, then blank both of the text fields.
    if (!$isNumeric) {
        $srcAmnt = '';
        $dstAmnt = '';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ryan's Super F/X Calculator</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/main.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Ryan's Super F/X Calculator</h1>
            <h2><?php
                echo 'Welcome ' . $_SESSION[LoginDataModel::USERNAME_SESSION_KEY] . '!';
                ?></h2>
        </header>
        <hr>

        <form name="fxCalc" action="fxCalc.php" method="post">
            <div class="formMain">
                <h3>Choose your source currency code and enter the amount as precise as you want it.</h3>
                <select name="<?php echo $fxIniArray[FxDataModel::SRC_CUCY_KEY]; ?>">
                    <?php
                    for ($i = 0; $i < count($fxCurrencies); $i++) {
                        if ($_POST[$fxIniArray[FxDataModel::SRC_CUCY_KEY]] === $fxCurrencies[$i] && $isNumeric) {
                            echo '<option value="' . $fxCurrencies[$i] . '" selected>' . $fxCurrencies[$i] . '</option>';
                        } else {
                            echo '<option value="' . $fxCurrencies[$i] . '">' . $fxCurrencies[$i] . '</option>';
                        }
                    }
                    ?>
                </select>

                <input type="text" name="<?php echo $fxIniArray[FxDataModel::SRC_AMT_KEY]; ?>" value="<?php echo $srcAmnt; ?>">

                <h3>Now choose your destination currency code and click CALCULATE to receive your result!</h3>

                <select name="<?php echo $fxIniArray[FxDataModel::DST_CUCY_KEY]; ?>">
                    <?php
                    for ($i = 0; $i < count($fxCurrencies); $i++) {
                        if ($_POST[$fxIniArray[FxDataModel::DST_CUCY_KEY]] === $fxCurrencies[$i] && $isNumeric) {
                            echo '<option value="' . $fxCurrencies[$i] . '" selected>' . $fxCurrencies[$i] . '</option>';
                        } else {
                            echo '<option value="' . $fxCurrencies[$i] . '">' . $fxCurrencies[$i] . '</option>';
                        }
                    }
                    ?>
                </select>

                <input type="text" readonly name="<?php echo $fxIniArray[FxDataModel::DST_AMT_KEY]; ?>" value="<?php echo $dstAmnt; ?>">
                <br>
            </div>

            <div id="buttons">
                <input type="submit" value="CONVERT THAT CURRENCY!" name="submit">
                <input type="reset" value="RESET" name="reset" onclick="window.location.href = 'fxCalc.php'">
            </div>
        </form>

        <footer>
            <p>Copyright (c) 2019 Ryan Lasher. Unauthorized copying of my student work is not the right thing to do, but be inspired by the way I designed my page and come up with your own creative implementation!</p>
        </footer>
    </body>
</html>
