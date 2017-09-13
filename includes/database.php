<?php


function getFileMakerConnection()
{
	global $FM_CONNECTION, $FM_DATABASE, $FM_HOSTSPEC, $FM_USERNAME, $FM_PASSWORD;

	if (!isset($GLOBALS['FM_CONNECTION']) || !$FM_CONNECTION)
	{
		$FM_CONNECTION = new \FileMaker();
		$FM_CONNECTION->setProperty('database', $FM_DATABASE);
		$FM_CONNECTION->setProperty('hostspec', $FM_HOSTSPEC);
		$FM_CONNECTION->setProperty('username', $FM_USERNAME);
		$FM_CONNECTION->setProperty('password', $FM_PASSWORD);
		//echo "username: " . $FM_USERNAME . " password: " . $FM_PASSWORD . "<br/>";
	}

	return $FM_CONNECTION;
}

function throwExceptionOnFMError($obj)
{
	global $FM_CONNECTION;
	
	if ($FM_CONNECTION->isError($obj))
	{
		$backtrace = debug_backtrace();
		throw new \ErrorException($obj->getMessage() . "(code: {$obj->getCode()}, file: {$backtrace[0]['file']}, line: {$backtrace[0]['line']})");
	}
}

function login($userName, $password){

    //echo "attempting login with " . $userName . " and " . $password . "<br/>";

    //retrieve salt and hash
    $fm = getFileMakerConnection();
    $cmd = $fm->newFindCommand('PHP-USERS');
   	$cmd->addFindCriterion('username', '=='.$userName);
    
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
    	//echo "Error - " . $result->getMessage();
	    throw new \ErrorException("Login Failed");
    }

    
    $record = $result->getRecords();
    
    $user = new User(current($record));

    $_SESSION["access"] = $user->access;

    //echo "toHash: " . $user->salt . $password . "<br/>";
    //echo "retrievedHash: " . $user->hash . "<br/>";

    $testHash = md5( $user->salt . $password);

    //echo "testHash: " . $testHash . "<br/>";

    if($testHash !== $user->hash){

	    throw new \ErrorException("Login Failed");
    }


    return $user;
}



function getDBItems($page){
$fields = getFieldNames($page);
    $fm = getFileMakerConnection();
    $cmd = $fm->newFindCommand('PHP-'.$page);
   	//cmd->addFindCriterion('username', '=='.$userName);
    
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
    	//echo "Error - " . $result->getMessage();
	    throw new \ErrorException("Get DBItems Failed");
    }

    
    $records = $result->getRecords();

    $toReturn = [];

    foreach ($records as $record) {

    	$dbItem = new DBItem();

    $dbItem->setDBItemFields($fields);
    $dbItem->reInit($record);
    	$toReturn[] = $dbItem;
    }


    return $toReturn;
}

function getFields($page){

    $fm = getFileMakerConnection();
    $cmd = $fm->newFindCommand('PHP-FORMFIELDS');
    $cmd->addFindCriterion('page', '=='.$page);
    if($_SESSION["access"] != "Internal"){
   	$cmd->addFindCriterion('access', '==All');
   }
    $cmd->addSortRule('sort', 1, FILEMAKER_SORT_ASCEND);
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
    	//echo "Error - " . $result->getMessage();
	    throw new \ErrorException("Get Fields Failed");
    }

    
    $records = $result->getRecords();

    $tabs = [];
    foreach ($records as $record) {
    	$field = new Field($record);
    	
        $parentTab = trim($field->parentTab);
        if(empty($parentTab)){
            if(empty($tabs[$field->tab])){
                $tabs[$field->tab] = [];
            }
            $tabs[$field->tab][] = $field;
        }else{
            if(empty($tabs[$parentTab][$field->tab])){
                $tabs[$parentTab][$field->tab] = [];

            }
            $tabs[$parentTab][$field->tab][] = $field;
            //var_dump($tabs[$parentTab]);
        }
    	
    }


    return $tabs;
}

function getFieldNames($page){

    $fm = getFileMakerConnection();
    $cmd = $fm->newFindCommand('PHP-FORMFIELDS');
    $cmd->addFindCriterion('page', '=='.$page);
    //echo "finding fields for page " . $page . "<br>";
    //cmd->addFindCriterion('username', '=='.$userName);
    $cmd->addSortRule('sort', 1, FILEMAKER_SORT_ASCEND);
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
        //echo "Error - " . $result->getMessage();
        throw new \ErrorException("Get Field Names Failed");
    }

    
    $records = $result->getRecords();

    $fields = [];
    foreach ($records as $record) {
        $field = new Field($record);
        $fields[] = $field->name;
    }

    return $fields;

}

function getSelectList($page){

    $fm = getFileMakerConnection();
    $cmd = $fm->newFindCommand('PHP-' . $page);
    //echo "finding fields for page " . $page . "<br>";
    //cmd->addFindCriterion('username', '=='.$userName);
    $cmd->addSortRule('name', 1, FILEMAKER_SORT_DESCEND);
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
        //echo "Error - " . $result->getMessage();
        throw new \ErrorException("Get Field Names Failed");
    }

    
    $records = $result->getRecords();

    $items = [];
    foreach ($records as $record) {
        $items[$record->getField('id')] = $record->getField('Name');
    }

    return $items;

}

function getDBItem($dbItemID,$page){

    $fields = getFieldNames($page);
    $fm = getFileMakerConnection();
    $cmd = $fm->newFindCommand('PHP-' .$page);
    $cmd->addFindCriterion('id', '=='.$dbItemID);
    //echo "getting Item from " . "PHP-" . $page . "<br/>";
    
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
        //echo "Error - " . $result->getMessage();
        throw new \ErrorException("Get DBItem Failed using dbItem id ". $dbItemID);
    }

    
    $records = $result->getRecords();

    $toReturn = null;
    if(count($records)> 0){
        $record = $records[0];
           $dbItem = new DBItem();

        $dbItem->setDBItemFields($fields);
        $dbItem->reInit($record);
       $toReturn = $dbItem;
    }
    return $toReturn;

}

function setDBItem($post,$page){
    $dbItemID = $post["id"];
    //echo "setting dbItem with id " . $dbItemID . "<br/>";
    $fields = getFieldNames($page);
    $fm = getFileMakerConnection();
    $cmd = $fm->newFindCommand('PHP-'.$page);
    $cmd->addFindCriterion('id', '=='.$dbItemID);
    
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
        //echo "Error - " . $result->getMessage();
        throw new \ErrorException("Get DBItem Failed using dbItem id ". $dbItemID);
    }

    
    $records = $result->getRecords();
    //echo "found " . count($records) . "<br/>";

    $dbItem = null;
    if(count($records)> 0){
        $record = $records[0];
           $dbItem = new DBItem();

        $dbItem->setDBItemFields($fields);
        $dbItem->reInit($record);
        //echo "record reinitialized <br/>";
    }

    if(!empty($dbItem)){
        //$dbItem->setName();
         //echo "setting store to ";
         //var_dump($post);
        //echo "records id is " . $record->getField('id') . " on dbItem its " . $dbItem->id . "<br/>";
        $dbItem->preSet($post);
         $dbItem->commitToDatabase();
        //echo "record committed";
    }
}

function newDBItem($post,$page){
    $dbItemID = $post["id"];
    $fields = getFieldNames($page);
    $fm = getFileMakerConnection();
    $cmd = $fm->newAddCommand('PHP-' . $page);
    
    $result = $cmd->execute();
    
    if ($fm->isError($result))
    {
        //echo "Error - " . $result->getMessage();
        throw new \ErrorException("New DBItem Failed ");
    }

    
    $records = $result->getRecords();

    $dbItem = null;
    if(count($records)> 0){
        $record = $records[0];
           $dbItem = new DBItem();

        $dbItem->setDBItemFields($fields);
        $dbItem->reInit($record);
    }

    if(!empty($dbItem)){
        //$dbItem->setName();
        // echo "setting store to ";
        // //var_dump($post);
        $dbItem->preSet($post);
         $dbItem->commitToDatabase();
        // echo "record committed";
    }
}
