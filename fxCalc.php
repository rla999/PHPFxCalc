<!DOCTYPE html>
<?php
 //Check if a session is already set! If it is, then bypass the login page.
if (!isset($_SESSION)) {
    session_start();
}
$username = "";

require_once('LoginDataModel.php');

require_once('FxDataModel.php');

if (isset($_SESSION[LoginDataModel::USERNAME])) {
    $username = $_SESSION[LoginDataModel::USERNAME];
    if (!isset($_SESSION[FxDataModel::FX_DM_KEY])) {
        $fxDataModel = new FxDataModel();
        $_SESSION[FxDataModel::FX_DM_KEY] = serialize($fxDataModel);
    } else {
        $fxDataModel = unserialize($_SESSION[FxDataModel::FX_DM_KEY]);
    }
} else {
    include LoginDataModel::LOGIN_FORM_URL;
    exit;
}


$fxCurrencies = $fxDataModel->getFxCurrencies();
$iniArray = $fxDataModel->getIniArray();

$srcCucy = $fxCurrencies[0];
$dstCucy = $fxCurrencies[0];
$srcAmt = "";
$dstAmt = "";
$error_message = "";

if (array_key_exists(filter_input(INPUT_POST, $iniArray[FxDataModel::SOURCE_AMOUNT_KEY]))) {
    $srcCucy = filter_input(INPUT_POST, $iniArray[FxDataModel::SOURCE_CURRENCY_KEY]);
    $dstCucy = filter_input(INPUT_POST, $iniArray[FxDataModel::DEST_CURRENCY_KEY]);
    $srcAmt = filter_input(INPUT_POST, $iniArray[FxDataModel::SOURCE_AMOUNT_KEY]);

    if ($srcCucy === $dstCucy) {
        $error_message = 'Warning! The source and destination currencies are the same. That means that the result will be 1.0; just thought you should know. :)';
        $srcCucy = $fxCurrencies[0];
        $dstCucy = $fxCurrencies[0];
        $srcAmt = "";
        $dstAmt = "";
    } else if ($srcAmt === false) {
        $error_message = 'Error! The source amount must be a valid number (decimals are allowed).';
        $srcCucy = $fxCurrencies[0];
        $dstCucy = $fxCurrencies[0];
        $srcAmt = "";
        $dstAmt = "";
    } else if ($srcAmt <= 0) {
        $error_message = 'Error! The source amount CANNOT be a negative number...at least not on planet Earth!';
        $srcCucy = $fxCurrencies[0];
        $dstCucy = $fxCurrencies[0];
        $srcAmt = "";
        $dstAmt = "";
    } else {
        $error_message = '';
        $dstAmt = sprintf("%.2f", $srcAmt * $fxDataModel->getFxRate($srcCucy, $dstCucy));
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
        <hr>
        <h2>Welcome <?php
                    echo $username . '!';
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
                            if ($fxCurrency === $srcCucy) {
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
                                if (!is_numeric($srcAmt)) {
                                    echo " ";
                                } else {
                                    echo $srcAmt;
                                }
                                ?>" class="textField" />

            <br />

            <h3>Now choose your destination currency code and click CALCULATE to receive your result!</h3>
            <select name="<?php
                            echo $iniArray[$fxDataModel::DEST_CURRENCY_KEY];
                            ?>">
                <?php
                foreach ($fxCurrencies as $newcurrency) {
                    ?>
                <option value="<?php
                                echo $newcurrency;
                                ?>" <?php
                                    if ($newcurrency === $dstCucy) {
                                        ?> selected <?php

                                    }
                                    ?>><?php
                                    echo $newcurrency;
                                    ?></option>
                <?php

            }
            ?>
            </select>

            <input type="text" disabled="disabled" name="<?php
                                                            echo $iniArray[$fxDataModel::DEST_AMOUNT_KEY];
                                                            ?>" id="outputText" class="textField" value="<?php
                                                                    if (isset($_POST[$fxDataModel::CONVERT_BUTTON])) {
                                                                        echo $dstAmt;
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