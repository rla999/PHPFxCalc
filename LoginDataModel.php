<?php

define("LOGIN_INI", "login.ini"); //Define the login INI file as a constant for use in the constructor.

class LoginDataModel {

    //Private data memebers for the class.
    private $loginArray;
    private $prepareStatement;

    //Constants for the class to be referred to elsewhere in our app.
    const USERNAME_KEY = 'username';
    const PASSWORD_KEY = 'password';
    const DSN_KEY = 'dsn';
    const DB_USERNAME_KEY = 'dbuser';
    const DB_PASSWORD_KEY = 'dbpass';
    const DB_PREP_STMT = 'loginPrepStmt';
    const USERNAME_SESSION_KEY = 'usernameSession';
    const LOGIN_PHP_FILENAME = 'login.php';

    //This constructor parses the login.ini file to create an array. It then creates a new PDO object using the database connection info given in the INI file. It then runs the SQL statement to get access to the users table.
    public function __construct() {

        $this->loginArray = parse_ini_file(LOGIN_INI);
        $loginPDO = new PDO(
                $this->loginArray[self::DSN_KEY],
                $this->loginArray[self::DB_USERNAME_KEY],
                $this->loginArray[self::DB_PASSWORD_KEY]
        );

        $this->prepareStatement = $loginPDO->prepare($this->loginArray[self::DB_PREP_STMT]);
    }

    //This destructor function destroys the login session when called.
    public function __destruct() {
        $loginPDO = null;
    }

    //This function checks to see if the user and password match an entry in the database. When it's done it uses the closeCursor method to free up resources.
    public function validateUser($username, $password) {
        $this->prepareStatement->bindParam(':username', $username);
        $this->prepareStatement->bindParam(':password', $password);

        $this->prepareStatement->execute();

        if ($this->prepareStatement->rowCount()) {
            return true;
        } else {
            return false;
        }

        $this->prepareStatement->closeCursor();
    }

    //Returns the array created from parsing the login.ini file.
    public function getLoginArray() {
        return $this->loginArray;
    }

}

?>