<?php

/** Import the library **/
require_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'ini.php');

/**
 * @function perform command line test
 * @param  void
 * @return void
 */
function testSQLayer() {

    $start = microtime(true);

    echo '--------------TEST SQLAYER---------------'.PHP_EOL;

    /** access table singleton **/
    $example = SQLayerExampleTable::tbl();

    $example->createTable();
    
    echo '--------------TEST INSERT---------------'.PHP_EOL;

    /** test explicit insertion with key **/
    $example->insertRec(array(1,'AA','Alcoa Inc'));

    /** test keyless insert **/
    $insert_id = $example->insertRec(array('BA','Boeing Co'));
    echo 'Insert ID was '.$insert_id.PHP_EOL;

    /** test insert with commas **/
    $example->insertRec(array(3,'CAT','Caterpillar, Inc'));

    echo '--------------TEST ALLRECS---------------'.PHP_EOL;

    /** print table records **/
    print_r($example->allRecs());

}

/** call the function **/
testSQLayer();
