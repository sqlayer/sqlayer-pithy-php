<?php

/**
 * SQLayerXLS
 * Class for conversion of table data between XLS and array of arrays
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
 * file_put_contents(SQLayerXLS::data(),$path).
 */

class SQLayerXLS extends SQLayerData
{

    /** @method convert data to array of rows
      * @param  *str* comma separated values
      * @return void **/
    public function dataToRows($str)
    {
        $this->data = $str;
        $this->rows = array();
        
        /** set up a temporary array to hold xml row strings **/
        $xrs = array();
        
        while ($stp = strpos($str, '<Row')) {
        
            /** trim anything before the next row **/
            $str = substr($str, $stp);
            
            /** find position of the closing tag **/
            $end = strpos($str, '</Row') + 6;
            
            /** add the xml to the temp array **/
            $xrs[] = substr($str, 0, $end);
            
            /** strip out what has been extracted**/
            $str = substr($str, $end);
        
        }
        
        /** convert the cells to values **/
        foreach ($xrs as $xr) {
        
            $values = array();
            
            while ($stp = strpos($xr, '<Data')) {
            
                /** trim anything before the next cell **/
                $xr = substr($xr, $stp);
                
                /** find the closing tag **/
				$end = strpos($xr, '</Data') + 7;
				
				/** add the cell to the values array **/
				$values[] = strip_tags(substr($xr, 0, $end));
				
				/** strip out what has been extracted **/
				$xr = substr($xr, $end);
				
            }
            
            $this->rows[] = $values;
        
        }
        
    }
    
    /** @method convert array of rows to data
      * @param  *arr* array of row arrays
      * @return void **/
    public function rowsToData($rows)
    {
        $this->data = '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40"><DocumentProperties xmlns="urn:schemas-microsoft-com:office:office"><Author>SQLayer</Author><LastAuthor> SQLayer </LastAuthor><Created>'.date('D M d H:i:s').' EST '.date('Y').'</Created><LastSaved>'.date('D M d H:i:s').' EST '.date('Y').'</LastSaved><Company>ROLCapital, Inc.</Company><Version>11.9999</Version></DocumentProperties><ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel"><WindowHeight>10760</WindowHeight><WindowWidth>20480</WindowWidth><WindowTopX>0</WindowTopX><WindowTopY>0</WindowTopY><ProtectStructure>False</ProtectStructure><ProtectWindows>False</ProtectWindows></ExcelWorkbook>'.PHP_EOL.'<Worksheet>'.PHP_EOL;
        $this->rows = $rows;
        
        foreach ($rows as $row) {
            
            $this->data .= '<Row>'.PHP_EOL;
            
            foreach ($row as $value) {
            
                $this->data .= '<Cell><Data>';
                
                $this->data .= $value;
            
                $this->data .= '</Data></Cell>'.PHP_EOL;
            }
            
            $this->data .= '</Row>'.PHP_EOL;

        }

        $this->data .= '</Worksheet>'.PHP_EOL.'</Workbook>'.PHP_EOL;

    }

}
