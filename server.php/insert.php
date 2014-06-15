<?php
include 'connect.php'; //Connect

//retrieve the data
$name = $_REQUEST['name'];
$value = $_REQUEST['value'];

date_default_timezone_set('Asia/Calcutta');
$d = date('Y-m-d');
$t = date('H:i:s');



$sql = "INSERT INTO eil(name,value,entryDate,entryTime) VALUES('$name', '$value', '$d', '$t')";

if (!mysql_query($sql))
{
   die('Error: ' . mysql_error());
}

mysql_close();


?>
