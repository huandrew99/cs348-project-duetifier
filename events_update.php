<?php

require("assets/functions/config.php");
mysqli_query($connection, "SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");//JBZ
/* Values received via ajax */
$id = $_POST['id'];
$start = $_POST['start'];
$end = $_POST['end'];

// update the records

$sql = "UPDATE events SET start=?, end=? WHERE event_id=?";
$q = $db->prepare($sql);
$q->execute(array($start,$end,$id));

?>
