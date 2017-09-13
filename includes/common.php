<?php
session_start();
require_once '/home/mcs/FileMaker12/FileMaker.php';
require_once 'config.php';
require_once 'includes/database.php';
$relative_path =  $_SERVER['PHP_SELF'];
if($_SESSION["logged_in"] != "true" && strpos($relative_path, "login.php") === false){
    
    redirectRequest("login.php");
}



function __autoload($fullClass)
{

    $classpath = explode('\\', $fullClass);
    $class = end($classpath);

    if(!file_exists($filepath))
	$filepath = "classes/queries/{$class}.php";
    if(!file_exists($filepath))
	$filepath = "classes/data/{$class}.php";
    if(!file_exists($filepath))
	$filepath = "classes/forms/{$class}.php";
    if(!file_exists($filepath))
	$filepath = "classes/{$class}.php";



    if(file_exists($filepath))
	require_once($filepath);
    else
	throw new ErrorException("{$class} class not defined.");
}

function redirectRequest($location, $code=302)
{
	header("Location: $location", false, $code);
	exit();
}

function friendly($string){

    return str_replace(" ", "_", $string);
}

?>
