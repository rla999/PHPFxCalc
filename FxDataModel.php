<?php

define('FX_CALC_INI_FILE', 'fxCalc.ini'); //Define a constant for the relative location of the INI file.

class FxDataModel
{

    //IMPORTANT CONSTANTS!
    const DEST_AMOUNT_KEY = "dst.amt";
    const DEST_CUREENCY_KEY = "dst.cucy";
    const SOURCE_AMOUNT_KEY = "src.amt";
    const SOURCE_CURRENCY_KEY = "src.cucy";
    const FX_DM_KEY = "FxDataModel.php";
    const LOGIN_FORM_URL = "login.php";
    const FORM_NAME = "fxCalc.php";
    const FORM_NAME_TITLE = "fxCalc";
    const CONVERT_BUTTON = "convert";
    const RESET_BUTTON = "reset";
    const LOGOUT_BUTTON = "logout";

    const DBHANDLE = "db.handle";
    const DBUSER = "db.user";
    const DBPW = "db.pw";
    const SELECT_RATE_STATEMENT = 'rate.stmt';
    const RATE_ROW = 'rate.row';

    //DEFINE PRIVATE DATA MEMBERS:
    private $fxCurrencies; //CURRENCY CODES
    private $fxRates; //FX RATES ARRAY
    private $iniArray; //Associative array for INI file.
    private $rate_stmt; // RATE SELECT SQL STATEMENT

    /*
     * A no argument constructor. 
     * The constructor reads the ini file specified by the constant and stores the results in a private data member. 
     * The constructor will then read the rates file specified in the ini file.
     */

    public function __construct()
    {
        /*
         * This function reads the first line of currency codes and populate a private string array data member named fxCurrencies. 
         * Then the constructor reads the rest of the DB. 
         * In doing so it must build a private two-dimensional array data member named fxRates that contains the rates.
         */

        $fxPDO = new PDO($this->iniArray[self::DBHANDLE], $this->iniArray[self::DBUSER], $this->iniArray[self::DBPW]);
        $this->rate_stmt = $fxPDO->prepare($this->iniArray[self::SELECT_RATE_STATEMENT]);
        $this->rate_stmt->execute();
        while ($result = $this->rate_stmt->fetch()){
            $srcCucy = $result['srcCucy'];
            $this->fxCurrencies[] = $srcCucy;
            $dstCucy = $result["dstCucy"];
            $rate = $result["fxRate"];
            $this->fxRates[$srcCucy.$dstCucy] = $rate;
        }
        $this->rate_stmt->closeCursor();
        $fxPDO=null;

    }

    //Returns the array of country codes.
    public function getFxCurrencies()
    {
        return $this->fxCurrencies;
    }

    //Returns the associative array INI file.
    public function getIniArray()
    {
        return $this->iniArray;
    }

    //Returns the currency exchange rate.
    public function getFxRate($srcCucy, $dstCucy)
    {
        if($srcCucy===$dstCucy){
            return 1;
        }
        else{
            return $this->fxRates[$srcCucy.$dstCucy];
        }
    }
}

 