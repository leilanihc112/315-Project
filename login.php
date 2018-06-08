<!-- login.php -->

<?php
# Connect to database
$connect = mysql_connect("database.cse.tamu.edu", "XXXXXXXX", "XXXXXXXXX");
mysql_select_db("XXXXXXX", $connect);
	
# Get number of rows in the database
$result = mysql_query("SELECT * FROM `Arduino`", $connect);
$num_rows = mysql_num_rows($result);

# Selects Count in all rows
$res = mysql_query("SELECT * FROM `Arduino`");
# Save all Counts into an array
$n = 0;
while ($row = mysql_fetch_array($res)) 
{
	$rs_res[$n] = $row['Count'];
	$rs_rus[$n] = $row['DateTime'];
	$n = $n+1;
}
# Sort array in ascending order
sort($rs_res);

# If the table is empty, then change the auto-increment identity to 1
# This is to prevent the database from starting from a high number if the database is cleared
if ($num_rows == 0) 
{
	$alter = mysql_query("TRUNCATE TABLE `Arduino`", $connect);
}
# If the table has rows, make sure they go from 1 to max consecutively
# This prevents from having some numbers missing or starting from a number that isn't 1
else
{
	$i = 0;
	$p = 0;
	foreach ($rs_res as $a)
	{
		$i = $i+1;
		if ($a != $i) 
		{
			$update = mysql_query("UPDATE `Arduino` SET `Count`= '$i' WHERE `Count`='$a'", $connect);
		}
		$update = mysql_query("UPDATE `Arduino` SET `DateTime`='$rs_rus[$p]' WHERE `Count`='$i'", $connect);
		$p = $p+1;
	}	
	$i = $i+1;
	# Sets auto_increment to start from number following the last row
	$alter_2 = mysql_query("ALTER TABLE `Arduino` AUTO_INCREMENT=$i", $connect);
}
?>