<?php

/**
 * SQLayerJSON
 * Class for conversion of table data between JSON and array of arrays
 *
 * To see examples of extended classes, see SQLayerCSV, SQLayerJSON, and SQLayerXls
 *
 * @category   Frameworks
 * @package    SQLayer
 * @author     Roderic Linguri <rlinguri@mac.com>
 * @copyright  2016 Digices, Inc.
 * @license    https://opensource.org/licenses/MIT
 * @version    0.0.5
 * @link       http://www.sqlayer.com
 */

/**
 * NOTE: This class does not handle reading from or writing to a file path. You will need
 * to use file_get_contents($path) if reading from a file or, if writing out to file, use
 * file_put_contents(SQLayerJSON::data(),$path).
 */

class SQLayerJSON extends SQLayerData
{

    protected $keys;

    public function __construct() { }
    
    /** @method init with keys and rows (bypass constructor)
      * @param  *arr* keys, *arr* array of assoc rows
      * @return void **/
    public function initWithKeysAndRows($keys,$rows)
    {
        $this->keys = $keys;
        
        $this->rowsToData($rows);

    }

    /** @method init with string (bypass constructor)
      * @param  *str* JSON
      * @return void **/
    public function initWithString($json)
    {
        $this->dataToRows($json);

    }

    /** @method convert json string to rows
      * @param  *str* comma separated values
      * @return void **/
    public function dataToRows($str)
    {
        $this->data = $str;

        $objects = json_decode($str, false);

        $this->rows = array();

        foreach ($objects as $object) {

            $row = array();

            foreach ($object as $key=>$val) {
                $row[] = $val;
            }

            $this->rows[] = $row;

        }
        
    }
    
    /** @method convert array of rows to JSON string
      * @param  *arr* array of row arrays
      * @return void **/
    public function rowsToData($rows)
    {

        $this->rows = $rows;

        $object = array();

        foreach ($rows as $vals) {
        
            /* associate column names */
            $assoc = array();
            
            $x = 0;
            
            foreach ($vals as $key=>$val) {
            
                $assoc[$this->keys[$x]] = $val;
                
                $x++;
            }
            
            $object[] = $assoc;
            
        }
        
        $this->data = json_encode($object);
        
    }

}
