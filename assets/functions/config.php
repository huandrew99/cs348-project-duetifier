<?php 

    /* Settings for cloud */
    // These variables define the connection information for your MySQL database
    $username = getenv('MYSQL_USER');
    $password = getenv('MYSQL_PASSWORD');
    $inst = getenv('MYSQL_DSN');
    $dbname = getenv('MYSQL_DB');
    $host = null;
    
    /* Settings for local */
    // These variables define the connection information for your MySQL database
    // please use the following commented lines to replace the lines above
//    $username = "root";
//    $password = "";
//    $host = "localhost";
//    $dbname = "CS348";
//
//    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
//    try {
//    $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
//    }
//    catch(PDOException $ex) {
//        die("Database credentials are incorrect. {$ex->getMessage()}");
//    }
//    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//    header('Content-Type: text/html; charset=utf-8');
    
    
	// 1. Create a database connection
	$connection=mysqli_connect($host,$username,$password,$dbname,null,$inst);
	if (!$connection) {
		die("Database connection failed: " . mysqli_error());
	}

	// 2. Select a database to use 
	$db_selected = mysqli_select_db($connection, $dbname);
	if (!$db_selected) {
		die("Database selection failed: " . mysqli_error());
	}
    /* Prepared statements */
    $Classes = $connection->prepare("select * from class ORDER BY class_no ASC");
    $Types = $connection->prepare("select * from type ORDER BY importance ASC");
    $Colors = $connection->prepare("SELECT * FROM color");
    $Events = $connection->prepare("select * from events");
    
	
    session_start(); 
?>
