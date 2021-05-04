<?php
require 'assets/functions/functions.php';
$id = $_GET['id'];

// stored procedures 
$conection->query("call delEvent('" . $id . "')");

?>

<script>
location.href='index.php';
</script>


