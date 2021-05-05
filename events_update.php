<?php
require("assets/functions/config.php");
mysqli_query($connection, "SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");//JBZ
/* Values received via ajax */
$id = $_POST['id'];
$start = $_POST['start'];
$end = $_POST['end'];
    
/* Set transaction level */
$connection->query("SET TRANSACTION ISOLATION LEVEL LEVEL SERIALIZABLE");
/* Start transaction */
mysqli_begin_transaction($connection);
try {
    /* Stored procedures */
    // update the records
    $connection->query("call upEventTime('".$id."', '".$start."', '".$end."')");
    mysqli_commit($connection);
    /* If code reaches this point without errors then commit the data in the database */
} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($connection);
    throw $exception;
}
    
?>
