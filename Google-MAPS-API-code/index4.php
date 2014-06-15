<?php
$value = $_REQUEST['value'];
$File = "index4.txt";
$Handle = fopen($File, 'w');
fwrite($Handle, $value);
print "Data Written";
fclose($Handle);
?>
