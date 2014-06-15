<?php
 include 'connect.php'; //Connect

$sql = "SELECT * FROM eil";

$result = mysql_query($sql);
?>
<html>
<head>
<title>EILabz</title>
</head>
<body>
<table align="center" border="1">
<tr>
<th>Name</th><th>Value</th><th>Entered At</th>
</tr>
<?php while($r=mysql_fetch_assoc($result)){ ?>
<tr>
<td><?php echo $r['name']; ?></td><td><?php echo $r['value']; ?></td><td><?php echo date('d-m-Y',strtotime($r['entryDate']))." , ".$r['entryTime']; ?></td><td><a href="delete.php?id=<?php echo $r['id']; ?>" >Delete</a></td>
</tr>
<?php } ?>
</table>
</body>
</html>
