<?php

define('FX_CALC_INI_FILE', 'fxCalc.ini'); //Define a constant for the relative location of the INI file.

class FxDataModel
{

    //IMPORTANT CONSTANTS!
    const DEST_AMOUNT_KEY = "dst.amt";
    const DEST_CUREENCY_KEY = "dst.cucy";
    const FX_RATES_CSV_KEY = "fx.rates.file";
    const SOURCE_AMOUNT_KEY = "src.amt";
    const SOURCE_CURRENCY_KEY = "src.cucy";
    const FX_DM_KEY = "FxDataModel.php";
    const LOGIN_FORM_URL = "login.php";
    const FORM_NAME = "fxCalc.php";
    const FORM_NAME_TITLE = "fxCalc";
    const CONVERT_BUTTON = "convert";
    const RESET_BUTTON = "reset";
    const LOGOUT_BUTTON = "logout";

    //DEFINE PRIVATE DATA MEMBERS:
    private $fxCurrencies; //CURRENCY CODES
    private $fxRates; //FX RATES CSV
    private $iniArray; //Associative array for INI file.

    /*
     * A no argument constructor. 
     * The constructor reads the ini file specified by the constant and stores the results in a private data member. 
     * The constructor will then read the rates file specified in the ini file.
     */

    public function __construct()
    {
        /*
         * This function reads the first line of currency codes and populate a private string array data member named fxCurrencies. 
         * Then the constructor reads the rest of the file. 
         * In doing so it must build a private two-dimensional array data member named fxRates that contains the rates.
         */

        $this->iniArray = parse_ini_file(FX_CALC_INI_FILE); //Parse the INI file and assign it to the array variable.
        if (($handle = fopen($this->iniArray[self::FX_RATES_CSV_KEY], 'r')) !== false) { //Open the fxRates.csv file and create a handle variable to reference elsewhere.
            if (($this->fxCurrencies = fgetcsv($handle)) !== false) { //This function reads the CSV file from the handle.
                while (($this->fxRates[] = fgetcsv($handle, 'r')) !== false) { //Populate fXRates array.
                    continue;
                }
            }
        }
        fclose($handle); //close the file using the assigned currency handle.
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
    public function getFxRate($sourceCurrency, $destCurrency)
    {
        $in = 0;
        $len = count($this->fxCurrencies);
        $out = 0;

        for (; $in < $len; $in++) { //Assign the 'in' rate to SOURCE_CURRENCY_KEY
            if ($this->fxCurrencies[$in] == $sourceCurrency) {
                break;
            }
        }

        for (; $out < $len; $out++) { //Assign the 'out' rate to DEST_CUREENCY_KEY
            if ($this->fxCurrencies[$out] == $destCurrency) {
                break;
            }
        }

        return $this->fxRates[$in][$out];
    }
}

 