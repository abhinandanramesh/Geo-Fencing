<?php
$value = $_REQUEST['value'];
$status = $_REQUEST['status'];
$datawritten = $value.' Status '.$status;
$File = "data.txt";
$Handle = fopen($File, 'a');
fwrite($Handle, $datawritten . PHP_EOL);
//print "Data Written";
fclose($Handle);


$File2 = "status.txt";
$Handle2 = fopen($File2, 'w');
fwrite($Handle2, $status);
//print "Data Written";
fclose($Handle2);

?>
