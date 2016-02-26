<?php

/**
 * SQLayerDbo
 * Abstract class to provide PDO functionality to extended classes
 *
 * To see examples of extended class, see SQLayerExampleDbo
 *
 * @category   Frameworks
 * @package    SQLayer
 * @author     Roderic Linguri <rlinguri@mac.com>
 * @copyright  2016 Digices, Inc.
 * @license    https://opensource.org/licenses/MIT
 * @version    0.0.5
 * @link       http://www.sqlayer.com
 */

abstract class SQLayerDbo
{

    /** @property *str* database directory **/
    protected $dbDir;
    
    /** @property *str* database name **/
    protected $dbName;
    
    /** @property *str* database title **/
    protected $dbTitle;
    
    /** @property *obj* PDO Connection **/
    protected $pdo;
    
    /** @method constructor
      * @param  void
      * @return void **/
    public function __construct()
    {
        
        /** check if directory exists (create if necesssary) **/
        if (!file_exists($this->dbDir)) {
            mkdir($this->dbDir, 0777, true);
        }

        /** check if file will be created (i.e. "true" means file does not exist) **/
        $flag = ( !file_exists($this->dbDir.DIRECTORY_SEPARATOR.$this->dbName.'.db') ? true : false );

        /** create the connection **/
        $this->pdo = new PDO('sqlite:'.$this->dbDir.DIRECTORY_SEPARATOR.$this->dbName.'.db');
        
        /** set up database **/
        if ($flag == true) {
            $this->initializeDB();
        }

    }

    /** @method prevent duplication of this object
      * @param  void
      * @return void **/
    public function __clone() { }

    /** @method initialize database (create/populate indexes/tables)
      * @param  void
      * @return void **/
    protected function initializeDB()
    {
        // if you do not override this, nothing will happen
    }

    /** @method fetch records
      * @param  *str* SQL Statement
      * @return *arr* array of assocs (or false) **/
    public function fetchRecs($sql)
    {
        /** make sure we have a result **/
        if ($result = $this->pdo->query($sql, PDO::FETCH_ASSOC)) {

            /** initialize array for records to return **/
            $recs = array();

            /** add the records from result **/
            foreach ($result as $rec) {
                $recs[] = $rec;
            }

            /** make sure we are not returning an empty array **/
            if (count($recs) > 0) {
                return $recs;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    /** @method fetch a single record
      * @param  *str* SQL Statement
      * @return *arr* assoc (or false) **/
    public function fetchRec($sql)
    {
        /** return the first record (or false) **/
        if ($recs = $this->fetchRecs($sql)) {
            foreach ($recs as $rec) {
                return $rec;
            }
        } else {
            return false;
        }
    }
    
    /** @method executes an SQL Statement
      * @param  *str* SQL Statement
      * @return *int* affected rows (or false) **/
    public function executeSQL($sql)
    {
        return $this->pdo->exec($sql);
    }

    /** @method passthru of PDO method
      * @param  void
      * @return void **/
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

}
