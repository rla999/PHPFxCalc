<?php

//Define the fxCalc.ini file as a constant to be used in the constructor.
define("FX_CALC_INI", "fxCalc.ini");

//Include the error data model class for our custom exception handling.
include_once('ErrorDataModel.php');

class FxDataModel
{

    //Constants for the class. 
    const DST_AMT_KEY = 'dst.amt';
    const DST_CUCY_KEY = 'dst.cucy';
    const SRC_AMT_KEY = 'src.amt';
    const SRC_CUCY_KEY = 'src.cucy';
    const FX_RATE = 'fx.rate';
    const FX_SESSION_KEY = 'fxDataModelInstance';
    const FX_PHP_FILENAME = 'fxCalc.php';
    const DSN_KEY = 'dsn';
    const DB_USERNAME_KEY = 'dbuser';
    const DB_PASSWORD_KEY = 'dbpass';
    const DB_PREP_STMT = 'fxPrepStmt';

    //Private data members for the class.
    private $currencyCodes = array();
    private $fxRates = array();
    private $iniArray;

    /*
     * This constructor does a lot! First, it parses the fxCalc.ini file.
     * Next, it creates a new PDO object to access the MySQL database using the info supplied in the INI file.
     * Then it runs the prepared SQL statement in the INI file to return the exchanges rates, ordered by the source and desintation currencty codes.
     * Then we use the fetchAll method to store all the retrieved data into a local array.
     * It outputs into a multi-dimensional array sort of like it was back in Lab 3.
     * Nice thing about this is that if data in the DB changed, the data stored in the local array would change, too.
     * After all of the pushing of data into the array which it only does after checking to see if the data is already there...
     * It then associates the value of the source and destination currency keys concatenated together as the fxrate column value.
     * Tben the connection to the database is closed and the DB object is set to null.
     * This approach I think is better than just relying on a DB connection the whole time the app is running.
     * It gets what it needs from the DB, writes it to a local array, and then closes the DB connection.
     * Also this approach got rid of those pesky undefined index warnings when I was viewing my app.
     */

    public function __construct()
    {
        $this->iniArray = parse_ini_file(FX_CALC_INI);

        try {
            $fxPDO = new PDO(
                $this->iniArray[self::DSN_KEY],
                $this->iniArray[self::DB_USERNAME_KEY],
                $this->iniArray[self::DB_PASSWORD_KEY]
            );

            $prepareStatement = $fxPDO->prepare($this->iniArray[self::DB_PREP_STMT]);

            $prepareStatement->execute();

            $data = $prepareStatement->fetchAll(PDO::FETCH_ASSOC);

            $len = count($data);
            for ($i = 0; $i < $len; $i++) {
                if (in_array($data[$i][$this->iniArray[self::SRC_CUCY_KEY]], $this->currencyCodes)) { } else {
                    array_push($this->currencyCodes, $data[$i][$this->iniArray[self::SRC_CUCY_KEY]]);
                }

                if (in_array($data[$i][$this->iniArray[self::DST_CUCY_KEY]], $this->currencyCodes)) { } else {
                    array_push($this->currencyCodes, $data[$i][$this->iniArray[self::DST_CUCY_KEY]]);
                }

                $this->fxRates[$data[$i][$this->iniArray[self::SRC_CUCY_KEY]] . $data[$i][$this->iniArray[self::DST_CUCY_KEY]]] = $data[$i][$this->iniArray[self::FX_RATE]];
            }

            $prepareStatement->closeCursor();
            $fxPDO = null;
        } catch (PDOException $e) {
            header(ErrorDataModel::getErrorUrl($e->getMessage()));
            exit;
        }
    }

    //Returns the array of country codes.
    public function getFxCurrencies()
    {
        return $this->currencyCodes;
    }

    /*Returns the array of F/X rates given a source currency code and a destination currency code. 
    Returns 1.0 if the user selects the same code for both.
    Starting with lb 7, only source -> destination direction is stored in DB. 
    The reciprocal (for when user wants destination -> source) is dervied in the new version of the getFxRte function.
    */
    public function getFxRate($srcCurrency, $dstCurrency)
    {
        $srcCurrency = $this->currencyCodes[$srcCurrency];
        $dstCurrency = $this->currencyCodes[$dstCurrency];

        if ($srcCurrency === $dstCurrency) {
            return 1.0;
        } else {
            if (array_key_exists($srcCurrency . $dstCurrency, $this->fxRates)) {
                return $this->fxRates[$srcCurrency . $dstCurrency];
            } else {
                return (1.0 / $this->fxRates[$dstCurrency . $srcCurrency]);
            }
        }
    }

    //Returns the converted value (dstAmnt in the form). Better to have it in the data model instead of having it locally defined on the form page itself like in my previous labs.
    public function getOutput($srcAmnt, $srcCurrency, $dstCurrency)
    {
        //        return $srcAmnt * (double) $this->getFxRate($srcCurrency, $dstCurrency);
        return number_format($srcAmnt * (double)$this->getFxRate($srcCurrency, $dstCurrency), 2);
    }

    //Returns the array created by the INI file.
    public function getIniArray()
    {
        return $this->iniArray;
    }
}
