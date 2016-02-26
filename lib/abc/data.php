<?php

/**
 * SQLayerData
 * Abstract class for conversion of table data between string and array of assoc
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
 * file_put_contents(SQLayerData::data(),$path).
 */

abstract class SQLayerData
{

    /** @property *str* file contents **/
    protected $data;
    
    /** @property *arr* arr if assoc **/
    protected $rows;
    
    /** @method constructor
      * @param  *str* single line of comma separated values
      * @return *arr* values **/
    public function __construct()
    {
        $args = func_get_args();
        
        if (isset($args)) {
        
            if (is_array($args[0])) {
        
                /** process array into string **/
                $this->rowsToData($args[0]);
        
            } elseif (is_string($args[0])) {
            
                /** process string into array **/
                $this->dataToRows($args[0]);
            
            }

        }
    }

    /** @method rows getter
      * @param  void
      * @return *arr* of arrays **/
    public function rows()
    {
        return $this->rows;
    }

    /** @method data getter
      * @param  void
      * @return *str* file data **/
    public function data()
    {
        return $this->data;
    }

}
