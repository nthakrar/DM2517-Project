<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST'); 
?>
<?php
	include_once 'includes/config.php';
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
?>
<?php
	
	
	$query = "SELECT title FROM series";
		
		if($result = $mysqli->query($query)){
			$return_str="";
			while($row = $result->fetch_assoc()){
				
				// echo $row["title"];
				$return_str .=$row["title"];
								
			}
			mysqli_free_result($result);	
		}
		// echo $return_str;
		echo $return_str;

?>
