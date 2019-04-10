<?php

// This class handles our custom exception handling that redirects users to a more useful page if there is an error instead of the default PHP page.

require_once("IError.php");

class ErrorDataModel implements IError
{

    public static function getErrorURL($msg)
    {
        return header(IError::URL_KEY . IError::ERR_MSG_KEY . '=' . urlencode($msg));
    }
}
