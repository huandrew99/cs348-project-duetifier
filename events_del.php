<?php
require 'assets/functions/functions.php';
$id = $_GET['id'];
    
/* Set transaction level */
$connection->query("SET TRANSACTION ISOLATION LEVEL LEVEL SERIALIZABLE");
mysqli_begin_transaction($connection);
try {
    /* Stored procedures */
    // delete the event
    $connection->query("call delEvent('".$id."')");
    mysqli_commit($connection);
    /* If code reaches this point without errors then commit the data in the database */
} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($connection);
    throw $exception;
}
?>

<script>
location.href='index.php';
</script>


