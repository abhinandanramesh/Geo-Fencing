<?php
$value = $_REQUEST['value'];
$File = "YourFile.txt";
$Handle = fopen($File, 'w');
fwrite($Handle, $value);
//print "Data Written";
fclose($Handle);
?>
