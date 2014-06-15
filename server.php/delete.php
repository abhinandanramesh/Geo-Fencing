<?php
include 'connect.php'; //Connect

//retrieve the data
$id = $_REQUEST['id'];


$sql = "DELETE FROM eil WHERE id='".$id."'";

if (!mysql_query($sql))
{
   die('Error: ' . mysql_error());
}

mysql_close();

header("Location: display.php");


?>
