<?php

/**
 * SQLayer Initialization
 * Require this file to import the library.
 *
 * @category   Frameworks
 * @package    SQLayer
 * @author     Roderic Linguri <rlinguri@mac.com>
 * @copyright  2016 Digices, Inc.
 * @license    https://opensource.org/licenses/MIT
 * @version    0.0.5
 * @link       http://www.sqlayer.com
 */

/** Check to see if the package constant has been defined (i.e pkg is already loaded) **/
if (!defined('SQLAYER')) {

    define('SQLAYER', __DIR__);

    /** Import all files in the 'lib' directory (which are in subdirectories) **/
    $lib = SQLAYER.DIRECTORY_SEPARATOR.'lib';
    $di = new DirectoryIterator($lib);
    foreach ($di as $dir) {
        $dn = $dir->getFilename();
        if (substr($dn, 0, 1) != '.') {
            $cla = $lib.DIRECTORY_SEPARATOR.$dn;
            $sd = new DirectoryIterator($cla);
            foreach ($sd as $file) {
                $fn = $file->getFilename();
                if (substr($fn, 0, 1) != '.') {
                    require_once($cla.DIRECTORY_SEPARATOR.$fn);
                }
            }
        }
    }

}
