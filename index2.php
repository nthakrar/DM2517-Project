<?php include 'prefix.php'; ?>
	
<root>
<seriescatalog>
<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	

	$to_present = db_query_series($mysqli);
	print utf8_encode($to_present);
	//subscribe("24", $mysqli);
		

?>
</seriescatalog>
</root>
<?php include 'postfix.php'; ?>
