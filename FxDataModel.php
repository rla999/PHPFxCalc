<?php

define('FX_CALC_INI_FILE', 'fxCalc.ini'); //Define a constant for the relative location of the INI file.

class FxDataModel {

    //IMPORTANT CONSTANTS!
    const DEST_AMOUNT_KEY = "dst.amt";
    const DEST_CURRENCY_KEY = "dst.cucy";
    const SOURCE_AMOUNT_KEY = "src.amt";
    const SOURCE_CURRENCY_KEY = "src.cucy";
    const FX_DM_KEY = "FxDataModel.php";
    const FX_RATE = "fx.rate";
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

    //DEFINE PRIVATE DATA MEMBERS:
    private $fxCurrencies = array(); //CURRENCY CODES
    private $fxRates = array(); //FX RATES ARRAY
    private $iniArray; //Associative array for INI file.
    private $result;

    public function __construct() {
        $this->iniArray = parse_ini_file(FX_CALC_INI_FILE);
        if (!isset($this->iniArray[self::DBPW])) {
            $fxPDO = new PDO($this->iniArray[self::DBHANDLE], $this->iniArray[self::DBUSER], NULL);
        } else {
            $fxPDO = new PDO($this->iniArray[self::DBHANDLE], $this->iniArray[self::DBUSER], $this->iniArray[self::DBPW]);
        }
        $rate_stmt = $fxPDO->prepare($this->iniArray[self::SELECT_RATE_STATEMENT]);
        $rate_stmt->execute();
        $this->result = $rate_stmt->fetchAll();
        $rate_stmt->closeCursor();

        $this->fxCurrencies = array_unique(array_column($this->result, $this->iniArray[self::SOURCE_CURRENCY_KEY]));
        foreach ($this->result as $row) {
            foreach ($row as $rowKey => $value) {
                switch ($rowKey) {
                    case $this->iniArray[self::SOURCE_CURRENCY_KEY]:
                        $src = $value;
                    case $this->iniArray[self::DEST_CURRENCY_KEY]:
                        $dst = $value;
                    case $this->iniArray[self::FX_RATE]:
                        $rate = $value;
                }
                $this->fxRates[$src . $dst] = $rate;
                if (!in_array($dst, $this->fxCurrencies)) {
                    $this->fxCurrencies[] = $dst;
                }
            }
            $fxPDO = null;
        }
    }

    public function getFxCurrencies() {
        return $this->fxCurrencies;
    }

    public function getIniArray() {
        return $this->iniArray;
    }

    public function getFxRate($srcCucy, $dstCucy) {
        if (isset($this->fxRates[$srcCucy . $dstCucy])) {
            return $this->fxRates[$srcCucy . $dstCucy];
        } else {
            return 1.0 / $this->fxRates[$dstCucy . $srcCucy];
        }
    }

}

?>