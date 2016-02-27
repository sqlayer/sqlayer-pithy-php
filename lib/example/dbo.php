<?php

/**
 * SQLayerExampleDbo
 * Example of SQLayerDbo implemented as singleton
 *
 * @category   Frameworks
 * @package    SQLayer
 * @author     Roderic Linguri <rlinguri@mac.com>
 * @copyright  2016 Digices, Inc.
 * @license    https://opensource.org/licenses/MIT
 * @version    0.0.5
 * @link       http://www.sqlayer.com
 */

class SQLayerExampleDbo extends SQLayerDbo
{

    /** *obj* the database singleton object **/
    protected static $dbo;
    
    /** @method dbo getter
      * @param  void
      * @return *obj* of this class **/
    public static function dbo()
    {
        if (!isset(self::$dbo)) {
            self::$dbo = new self();
        }
        return self::$dbo;
    }

    /** @method constructor
      * @param  void
      * @return void **/
    public function __construct()
    {
        /** define where the database file is stored **/
        $this->dbDir = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'db';
        
        /** the base name of the file (no extension) **/
        $this->dbName = 'example';
        
        /** create the database connection **/
        parent::__construct();
    }

}
