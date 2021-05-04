<?php
require 'assets/functions/functions.php';
$id = $_GET['id'];
mysqli_query($connection, "SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");//JBZ
// stored procedures 
$conection->query("call delEvent('" . $id . "')");

?>

<script>
location.href='index.php';
</script>


