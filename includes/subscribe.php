<?php
	include_once 'config.php'
	mysql_connect(HOST,USER,PASSWORD);
	mysql_select_db("thakrar");
	$user_id  = "nikhil";
	$title  = "24";
	sql="INSERT INTO (subsribes) VALUES ('$user_id', '$title')";
	$result=mysql_query($sql);
	if($result){
	echo "You have been successfully subscribed.";
?>