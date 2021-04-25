<?php
require 'assets/functions/functions.php';
$var = $_GET['id'];

$query = "DELETE FROM `$dbname`.`events` WHERE `events`.`event_id` = '$var' LIMIT 1";

mysqli_query($conection, $query) or die(mysqli_error());

?>

<script>
location.href='index.php';
</script>


