<?php 

    // These variables define the connection information for your MySQL database 
    $username = "root"; 
    $password = ""; 
    $host = "localhost"; 
    $dbname = "CS348";
	
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
    try { 
    $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
	}
	catch(PDOException $ex) { 
		die("Database credentials are incorrect. {$ex->getMessage()}");
	}
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
    header('Content-Type: text/html; charset=utf-8'); 
	
	
	
	// 1. Create a database connection
	$conection=mysqli_connect($host,$username,$password,$dbname);
	if (!$conection) {
		die("Database connection failed: " . mysqli_error());
	}

	// 2. Select a database to use 
	$db_selected = mysqli_select_db($conection, $dbname);
	if (!$db_selected) {
		die("Database selection failed: " . mysqli_error());
	}

	// prepared statements:
	$Classes = $conection->prepare("select * from class ORDER BY class_no ASC");
	$Types = $conection->prepare("select * from type ORDER BY importance ASC");
	$Colors = $conection->prepare("SELECT * FROM color");
	$Events = $conection->prepare("select * from events");
	
	
    session_start(); 
?>
