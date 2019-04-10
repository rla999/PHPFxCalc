<?php

require_once('IError.php');;
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>Error | Ryan's Super Awesome F/X Calculator
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <header>
        <h1>Money Banks Error</h1>
        <p>Sorry, an exception has occured. </p>
        <p>To continue, click the Back button in your browser.</p>
        <h2>Details on the error can be found below and sent to the website administrator:</h2>
        <p>Message: <?php
                    echo $_GET[IError::ERR_MSG_KEY]; ?></p>
    </header>
</body>

</html>