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

    <!-- I am using Bootstrap 4 to make styling easier. I may still use some custom CSS to override some styles if I want. -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-light">
    <div class="container center">
        <header>
            <h1 class="display-2 text-primary">Error in Ryan's Super F/X Calculator!</h1>
        </header>

        <h3 class="text-danger">Sorry, an exception has occured.</h3>
        <p>To continue, click the Back button in your browser.</p>
        <h2 class="text-info">Details on the error can be found below and sent to the website administrator:</h2>
        <p>Message: <?php
                    echo $_GET[IError::ERR_MSG_KEY]; ?></p>

        <footer class="font-italic text-secondary">
            <p>Copyright (c) 2019 Ryan Lasher. Unauthorized copying of my student work is not the right thing to do, but be inspired by the way I designed my page and come up with your own creative implementation!</p>
        </footer>
    </div>

</body>

</html>