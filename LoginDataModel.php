<?php

define('LOGIN_INI_FILE', 'login.ini');

class LoginDataModel
{

    //CONSTANTS
    const USERNAME = "username";
    const PASSWORD = "password";
    const FX_CALC_FORM_URL = "fxCalc.php";
    const LOGIN_BUTTON = "login";
    const RESET_BUTTON = "reset";
    const LOGIN_FORM_URL = "login.php";
    const LOGIN_FORM_NAME = "login.form";
    const LOGIN_DATA_MODEL = "LoginDataModel.php";

    const DBHANDLE = "db.handle";
    const DBUSER = "db.user";
    const DBPW = "db.pw";
    
    const SELECT_STATEMENT= "select.stmt";
    const BIND_USERNAME=":username";

    //Private data members
    private $loginArray;
    private $loginPDO;
    private $prep_stmt;

    //Login Data Model constructor
    public function __construct()
    {
        $this->loginArray = parse_ini_file(LOGIN_INI_FILE);
        $this->loginPDO = new PDO($this->loginArray[self::DBHANDLE],$this->loginArray[self::DBUSER],$this->loginArray[self::DBPW]);
        $this->prep_stmt=$this->loginPDO->prepare($this->loginArray[self::SELECT_STATEMENT]);
    }

    /*
     * This method accepts two strings meant to represent the username and password. 
     * By using the array of username => password pairs created at construction, 
     * this method can return TRUE if the pair agree; FALSE, otherwise. 
     */

    public function validateUser($username, $password)
    {
        $this->prep_stmt->bindParam(self::BIND_USERNAME, $username);
        if($this->prep_stmt->execute() && $row = $this->prep_stmt->fetch()){
            if($row[$this->loginArray[self::PASSWORD]]===$password){
                return true;
            }
        }
    }

    //Return the associative array for the login INI file.
    public function getLoginArray()
    {
        return $this->loginArray;
    }

    //Destroy the PDO object.
    public function __destruct() {
        $this->loginPDO=NULL;
    }
}

 