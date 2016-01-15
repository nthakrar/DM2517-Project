<?php
	
	include_once 'db_connect.php';
	include_once 'functions.php';
	
	sec_session_start();
	
	if(isset($_POST['user_id'], $_POST['p'])){
		$user_id = $_POST['user_id'];
		$password = $_POST['p'];	//The hashed password
		
		if(login($user_id, $password, $mysqli)){
			//login successful
			if($user_id=="admin"){
				header("Location: ../admin.php");			
			}else{
				header("Location: ../user.php");
			}
		}else{
			//login failed
			header("Location: ../index.php?err=loginFailed");
		}
	}else{
		 // The correct POST variables were not sent to this page. 
		echo 'Invalid Request';
	}

?>
