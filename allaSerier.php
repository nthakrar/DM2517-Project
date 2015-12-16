<?php 
	
	include_once 'includes/config.php';
	include_once "includes/db_connect.php";
	include_once "includes/functionsCopy.php";
?>
<?php
	print(db_query_series($mysqli, ""));
?>