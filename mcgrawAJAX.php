<?php
require_once('includes/common.php');

	$itemID = $_GET["o"];

	if(is_numeric($itemID)){
		$dbItem = getDBItem($itemID,$_GET["t"]);
	}

	if (! empty($dbItem)	) {
		//var_dump($asset->toArray());
	echo json_encode($dbItem->toArray());
	}
	else{
		echo "[]";
	}

