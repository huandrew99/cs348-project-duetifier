<?php

require("assets/functions/config.php");

/* Values received via ajax */
$id = $_POST['id'];
$start = $_POST['start'];
$end = $_POST['end'];
$course = $_POST['course'];
$title = $_POST["title"];
$importance = $_POST["importance"];
$note = $_POST['note'];
$course = $_POST['course'];

// update the records
// stored procedures

$conection->query("call upEvent('" . $id . "', '" . $course ."', '" . $title . "', '" . $importance . "', '" . $start . "', '" . $end . "', '" . $note . "')");

?>
