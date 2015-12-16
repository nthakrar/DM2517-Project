<?php
	
	include_once 'db_connect.php';
	$title=$_POST["title_startswith"];
	// echo($title);
	
	$return = "";
	$data=array();
	if($result = $mysqli->query("SELECT * FROM series WHERE title LIKE '$title%' ")){
		while($row = $result->fetch_assoc()){
			$db_title = $row["title"];
			// echo ("db_title: $db_title");
			array_push($data, $db_title);
		
		}
		echo json_encode($data);
	}else{
		echo "fail"; 
	}
		

	
?>