<?php

/**
 * SQLayerTable
 * Abstract class to represent a single SQLite table
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

abstract class SQLayerTable
{
    
    /** @property *str* table name **/
    protected $tableName;
    
    /** @property *obj* SQLayerDbo **/
    protected $dbo;
    
    /** @property *arr* array of SQLayerColumn objects **/
    protected $columns;
    
    /** @method get record from key
      * @param  *int* integer key
      * @return *arr* assoc (or false) **/
    public function recFromKey($key)
    {
        /** compose sql **/
        $sql = 'SELECT * FROM "'.$this->tableName.'" WHERE "k" = '.$key.';';

        /** return the first record (or false) **/
        if ($rec = $this->dbo->fetchRec($sql)) {
            return $rec;
        } else {
            return false;
        }
 
    }

    /** @method get record from key
      * @param  void
      * @return *arr* array of assocs (or false) **/
    public function allRecs()
    {
        /** compose sql **/
        $sql = 'SELECT * FROM "'.$this->tableName.'";';

        /** return the array of recs (or false) **/
        if ($recs = $this->dbo->fetchRecs($sql)) {
            return $recs;
        } else {
            return false;
        }

    }

    /** @method insert record
      * @param  *arr* simple array of values in sequence
      * @return *int* id (key) of inserted record **/
    public function insertRec($values)
    {
        /** compose sql **/
        $sql = 'INSERT INTO "'.$this->tableName.'" VALUES (';

        if (count($values) == (count($this->columns) - 1)) {
            /** prepend unquoted null, comma and opening quote **/
            $sql .= 'NULL, "';
        } elseif (count($values) == count($this->columns)) {
            /** prepend just the opening quote **/
            $sql .= '"';
        } else {
            /** get me out of here **/
            return false;
        }

        $sql .= implode('","', $values).'");';
        
        /** return the last insert id (or false) **/
        if ($result = $this->dbo->executeSQL($sql)) {
            return $this->dbo->lastInsertId();
        } else {
            return false;
        }

    }

    /** @method create table
      * @param  void
      * @return *int* 0 (or false) **/
    public function createTable()
    {
        /** compose sql **/
        $sql = 'CREATE TABLE "'.$this->tableName.'" (';
        
        $x = 1;
        foreach ($this->columns as $col) {
            $sql .= '"'.$col->char.'" '.$col->type;
            if ($x < count($this->columns)) {
                $sql .= ', ';
            }
            $x++;
        }
        $sql .= ');';
        
        /** return 0 for success or false **/
        return $this->dbo->executeSQL($sql);

    }

    /** @method table name getter
      * @param  void
      * @return *str* table name **/
    public function tableName()
    {
        return $this->tableName;
    }

    /** @method empty table
      * @param  void
      * @return *int* 0 (or false) **/
    public function emptyTable()
    {
        $sql = 'DELETE FROM "'.$this->tableName.'";';
        return $this->dbo->executeSQL($sql);
    }

    /** @method import from CSV
      * @param  *str* path [*bool* hasHeaders (default is false)]
      * @return void **/
    public function importFromCsv($path,$headers = false)
    {

        $csv = new SQLayerCsv(file_get_contents($path));
        
        $rows = $csv->rows();

        if ($headers == true) {
            array_shift($rows);
        }
        
        foreach ($rows as $row) {
            $this->insertRec($row);
        }
        
    }

    /** @method export to CSV
      * @param  *str* path [*bool* includeHeaders (default is false)]
      * @return void **/
    public function exportToCsv($path,$headers = false)
    {

        $dir = dirname($path);
        
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $rows = array();

        if ($headers == true) {
        
            $titles = array();
        
            foreach ($this->columns as $col) {
                $titles[] = $col->title;
            }
            
            $rows[] = $titles;

        }

        $recs = $this->allRecs();
        
        foreach ($recs as $rec) {
            $rows[] = array_values($rec);
        }
        
        $csv = new SQLayerCsv($rows);
        
        file_put_contents($path, $csv->file());

    }

    /** @method import from JSON
      * @param  *str* path
      * @return void **/
    public function importFromJson($path)
    {

        $json = new SQLayerJson();
        
        $json->fileToRows(file_get_contents($path));
        
        $rows = $json->rows();

        foreach ($rows as $row) {
            $this->insertRec($row);
        }
        
    }

    /** @method export to JSON
      * @param  *str* path
      * @return void **/
    public function exportToJson($path)
    {
        $dir = dirname($path);
        
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $keys = array();
        
        foreach ($this->columns as $col) {
            $keys[] = $col->varName;
        }

        $json = new SQLayerJson();
        
        $json->initWithKeysAndRows($keys,$this->allRecs());
        
        file_put_contents($path, $json->file().PHP_EOL);

    }

}
