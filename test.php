<?php 

// enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//connect to database
$username = "root"; 
$password = ""; 
$host = "localhost"; 
$dbname = "CS348";
$conection=mysqli_connect($host,$username,$password,$dbname);
if (!$conection) {
    die("Database connection failed: " . mysqli_error());
}
// $conection->query("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");


$id = 25;

// $query = "DELETE FROM `$dbname`.`events` WHERE `events`.`event_id` = '$var' LIMIT 1";
$conection->query("call delEvent('" . $id . "')");
// $result = $conection->query("call classes" );

// // 
// while ($row = mysqli_fetch_array($result)){     
//     echo "
//     <option value='" . $row['class_no'] . "'>" . $row['class_no'] . "</option>
//     ";
// }

// $result2 = $conection->query("select * from class ORDER BY class_no ASC");
// echo gettype($result);
// //loop the result set

// while ($row = mysqli_fetch_array($result2)){     
//     echo "
//     <option value='" . $row['class_no'] . "'>" . $row['class_no'] . "</option>
//     ";
// }




?>