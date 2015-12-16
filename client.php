<?php	
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	sec_session_start();
	if(isset($_POST["action"]) && !empty($_POST["action"])){
		$action = 	$_POST["action"];
		$title = $_POST["title"];
		$user_id = $_POST["user_id"];
		if(empty($user_id)){
			$user_id = $_SESSION["user_id"];
			
		}
		// print("client: " . $user_id);
		switch($action){
			// case 'subscribe': echo "Action: " . $action . " title: " . $title;//subscribe($title);
			case 'subscribe': subscribe($title, $mysqli); break;
			case 'remove_user': remove_user($user_id, $mysqli); break;
			case 'unsubscribe':unsubscribe($title, $mysqli, $user_id); break;
			case 'query_series': db_query_series_json($mysqli, $title); break;
		}
	}
	
	

?>
