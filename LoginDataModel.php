<?php

define('USER_INI_FILE', 'fxUsers.ini');
define('LOGIN_INI_FILE', 'login.ini');

class LoginDataModel {

    //CONSTANTS
    const USERNAME = "username";
    const PASSWORD = "password";
    const FX_USER_FILE = "fx.users.file";
    const FX_CALC_FORM_URL = "fxCalc.php";
    const LOGIN_BUTTON = "login";
    const RESET_BUTTON = "reset";
    const LOGIN_FORM_URL = "login.php";
    const LOGIN_FORM_NAME = "login.form";
    const LOGIN_DATA_MODEL = "LoginDataModel.php";

    //Private data members
    private $loginArray;
    private $userArray;

    //Login Data Model constructor
    public function __construct() {
        $this->userArray = parse_ini_file(USER_INI_FILE);
        $this->loginArray = parse_ini_file(LOGIN_INI_FILE);
    }

    /*
     * This method accepts two strings meant to represent the username and password. 
     * By using the array of username => password pairs created at construction, 
     * this method can return TRUE if the pair agree; FALSE, otherwise. 
     */

    public function validateUser($username, $password) {
        return array_key_exists($username, $this->userArray) &&
                ( $this->userArray[$username] == $password );
    }

    //Return the associative array for the login INI file.
    public function getLoginArray() {
        return $this->loginArray;
    }

    //Return the associative array for the fxUsers INI file.

    public function getUserArray() {
        return $this->userArray;
    }

}

?>
