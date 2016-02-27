<?php

/**
 * SQLayerExampleTable
 * Example of SQLayerTable implemented as singleton
 *
 * This class will reference a table called 'example' within a database called 'example'.
 * Reference the singleton by calling: SQLayerExampleTable::tbl();
 *
 * @category   Frameworks
 * @package    SQLayer
 * @author     Roderic Linguri <rlinguri@mac.com>
 * @copyright  2016 Digices, Inc.
 * @license    https://opensource.org/licenses/MIT
 * @version    0.0.5
 * @link       http://www.sqlayer.com
 */

class SQLayerExampleTable extends SQLayerTable
{

    /** *obj* table singleton **/
    protected static $tbl;
    
    /** @method tbl getter
      * @param  void
      * @return *obj* of this class **/
    public static function tbl()
    {
        if (!isset(self::$tbl)) {
            self::$tbl = new self();
        }
        return self::$tbl;
    }

    /** @method constructor
      * @param  void
      * @return void **/
    public function __construct()
    {
        $this->dbo =  SQLayerExampleDbo::dbo();
        $this->tableName = 'example';
        $this->columns = array(
            new SQLayerColumn('k','key','Key','INTEGER PRIMARY KEY',5),
            new SQLayerColumn('s','symbol','Symbol','TEXT',8),
            new SQLayerColumn('n','name','Name','TEXT',80)
        );
    }

}
